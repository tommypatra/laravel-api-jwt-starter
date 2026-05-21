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
        Schema::create('distribusi_mengajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_penjadwalan_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('program_studi_id')
                ->constrained()
                ->restrictOnDelete();
            $table->unsignedBigInteger('mata_kuliah_id')->index();
            $table->foreignId('dosen_id')
                ->constrained()
                ->restrictOnDelete();
            $table->smallInteger('semester');
            $table->string('kelas', 20);
            $table->tinyInteger('status_persetujuan')->default(1);
            $table->text('catatan_persetujuan')->nullable();
            $table->unique(['periode_penjadwalan_id', 'program_studi_id', 'mata_kuliah_id', 'dosen_id', 'semester', 'kelas'], 'distribusi_mengajar_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_mengajars');
    }
};
