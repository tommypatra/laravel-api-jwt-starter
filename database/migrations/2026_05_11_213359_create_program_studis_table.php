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
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_studi_siakad_id')->unique();
            $table->foreignId('fakultas_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('nama_program_studi', 180);
            $table->string('nama_singkat_program_studi', 180);
            $table->timestamp('updated_at_siakad')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
