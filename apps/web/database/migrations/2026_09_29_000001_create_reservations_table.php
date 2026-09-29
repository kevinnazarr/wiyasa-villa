<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('cabin_id')->index();
            $table->string('booking_code')->unique();
            $table->char('locale', 2)->default('id');
            $table->string('source');
            $table->string('status')->default('PENDING_PAYMENT');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('adults');
            $table->unsignedInteger('children');
            $table->unsignedInteger('infants');
            $table->unsignedInteger('total_guests');
            $table->char('currency', 3)->default('IDR');
            $table->unsignedBigInteger('subtotal_amount');
            $table->unsignedBigInteger('extra_guest_amount');
            $table->unsignedBigInteger('discount_amount');
            $table->unsignedBigInteger('total_amount');
            $table->timestamp('hold_expires_at')->nullable();
            $table->timestamp('price_locked_at')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->timestamps();

            $table->index(['cabin_id', 'check_in_date', 'check_out_date', 'status']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE reservations ADD CONSTRAINT reservations_check_out_after_check_in CHECK (check_out_date > check_in_date)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
