<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cabins', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('capacity');
            $table->unsignedSmallInteger('base_occupancy');
            $table->string('status')->default('ACTIVE');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('slug');
        });

        Schema::create('cabin_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('cabin_id')->constrained()->cascadeOnDelete();
            $table->char('locale', 2);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['cabin_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabin_translations');
        Schema::dropIfExists('cabins');
    }
};
