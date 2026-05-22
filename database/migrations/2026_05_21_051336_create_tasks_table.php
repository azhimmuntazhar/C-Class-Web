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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('course_key'); // 'struktur_data', 'algoritma_pemrograman', dll
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['individu', 'kelompok'])->default('individu');
            $table->string('material_link')->nullable();
            $table->string('submission_link')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('deadline_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
