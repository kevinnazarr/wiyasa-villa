<?php

use App\Actions\Reservation\CreateReservationHold;
use App\Models\Cabin;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

test('concurrent holds on same cabin allow exactly one (pgsql, fork barrier)', function (): void {
    if (! function_exists('pcntl_fork')) {
        $this->markTestSkipped('pcntl_fork() is not available.');
    }

    $host = (string) config('database.connections.pgsql.host', '127.0.0.1');
    $port = (string) config('database.connections.pgsql.port', '55432');
    $username = (string) config('database.connections.pgsql.username', 'wiyasa');
    $password = (string) config('database.connections.pgsql.password', '');
    $scratch = 'db_wiyasa_concurrency';

    try {
        $probe = new PDO(
            "pgsql:host={$host};port={$port};dbname=postgres;connect_timeout=2",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $probe = null;
    } catch (Throwable $e) {
        $this->markTestSkipped('PostgreSQL is unreachable at '.$host.':'.$port.'.');

        return;
    }

    $originalDefault = DB::getDefaultConnection();
    $originalPgsqlDatabase = (string) config('database.connections.pgsql.database');

    $adminDsn = "pgsql:host={$host};port={$port};dbname=postgres;connect_timeout=5";
    $admin = new PDO($adminDsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $exists = $admin->query(
        "SELECT 1 FROM pg_database WHERE datname = '{$scratch}'"
    )->fetchColumn();
    if ($exists) {
        $admin->exec("DROP DATABASE \"{$scratch}\" WITH (FORCE)");
    }
    $admin->exec("CREATE DATABASE \"{$scratch}\"");
    $admin = null;

    config()->set('database.connections.pgsql.database', $scratch);
    DB::purge('pgsql');
    DB::setDefaultConnection('pgsql');

    $exitCode = Artisan::call('migrate', ['--database' => 'pgsql', '--force' => true]);
    if ($exitCode !== 0) {
        throw new RuntimeException('Scratch database migration failed.');
    }

    $tmpDir = sys_get_temp_dir().'/wiyasa-conc-'.getmypid().'-'.uniqid();
    mkdir($tmpDir);
    $goFile = $tmpDir.'/GO';

    try {
        $user = User::factory()->create();
        $cabin = Cabin::factory()->create();
        $userId = $user->id;
        $cabinId = $cabin->id;

        $payload = function () use ($userId, $cabinId): array {
            return [
                'user_id' => $userId,
                'cabin_id' => $cabinId,
                'check_in' => '2026-10-10',
                'check_out' => '2026-10-12',
                'adults' => 2,
                'children' => 0,
                'infants' => 0,
                'total_guests' => 2,
                'subtotal_amount' => 1800000,
                'extra_guest_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 1800000,
            ];
        };

        // Close the shared PDO before forking so each child opens its own connection.
        DB::purge('pgsql');

        $workers = 2;
        $pids = [];
        for ($i = 0; $i < $workers; $i++) {
            $readyFile = $tmpDir.'/ready-'.$i;
            $resultFile = $tmpDir.'/result-'.$i.'.json';
            $pid = pcntl_fork();
            if ($pid === -1) {
                throw new RuntimeException('pcntl_fork() failed.');
            }
            if ($pid === 0) {
                // Child: fresh connection, never share the parent PDO across forks.
                DB::purge('pgsql');
                DB::setDefaultConnection('pgsql');
                file_put_contents($readyFile, (string) getmypid());

                $deadline = time() + 15;
                while (! file_exists($goFile)) {
                    if (time() > $deadline) {
                        file_put_contents($resultFile, json_encode(['status' => 'error', 'message' => 'Barrier timeout.']));
                        exit(0);
                    }
                    usleep(5000);
                }

                try {
                    $reservation = CreateReservationHold::run($payload());
                    file_put_contents($resultFile, json_encode(['status' => 'ok', 'reservation_id' => $reservation->id]));
                } catch (ValidationException $e) {
                    file_put_contents($resultFile, json_encode(['status' => 'validation_failed']));
                } catch (Throwable $e) {
                    file_put_contents($resultFile, json_encode(['status' => 'error', 'message' => get_class($e)]));
                }
                exit(0);
            }
            $pids[] = $pid;
        }

        // Parent: wait for both children to reach the barrier, then release them at once.
        $deadline = time() + 15;
        while (true) {
            $ready = 0;
            for ($i = 0; $i < $workers; $i++) {
                if (file_exists($tmpDir.'/ready-'.$i)) {
                    $ready++;
                }
            }
            if ($ready === $workers) {
                break;
            }
            if (time() > $deadline) {
                $this->fail('Concurrency barrier timed out waiting for workers.');
            }
            usleep(5000);
        }
        touch($goFile);

        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }

        // Parent reconnects fresh for assertions.
        DB::purge('pgsql');
        DB::setDefaultConnection('pgsql');

        $outcomes = [];
        for ($i = 0; $i < $workers; $i++) {
            $raw = file_exists($tmpDir.'/result-'.$i.'.json')
                ? file_get_contents($tmpDir.'/result-'.$i.'.json')
                : false;
            $outcomes[] = $raw !== false ? json_decode($raw, true) : ['status' => 'error'];
        }

        $ok = count(array_filter($outcomes, fn ($o): bool => ($o['status'] ?? '') === 'ok'));
        $conflicts = count(array_filter($outcomes, fn ($o): bool => ($o['status'] ?? '') === 'validation_failed'));

        expect($ok)->toBe(1)
            ->and($conflicts)->toBe(1)
            ->and(Reservation::query()->count())->toBe(1)
            ->and(Reservation::query()->active()->overlapping($cabinId, '2026-10-10', '2026-10-12')->count())->toBe(1);
    } finally {
        foreach (glob($tmpDir.'/*') ?: [] as $file) {
            @unlink($file);
        }
        @rmdir($tmpDir);

        DB::purge('pgsql');
        config()->set('database.connections.pgsql.database', $originalPgsqlDatabase);
        DB::setDefaultConnection($originalDefault);
        DB::purge('pgsql');

        $admin = new PDO($adminDsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $admin->exec("DROP DATABASE IF EXISTS \"{$scratch}\" WITH (FORCE)");
        $admin = null;
    }
});
