<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cabins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('capacity')->default(7);
            $table->unsignedSmallInteger('base_occupancy')->default(4);
            $table->string('status')->default('ACTIVE');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('slug');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE cabins ADD CONSTRAINT cabins_capacity_check CHECK (capacity > 0 AND capacity >= base_occupancy)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabins');
    }
};
