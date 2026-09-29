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
        Schema::create('cabin_translations', function (Blueprint $table) {
            $table->id();
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
    }
};
