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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('mata_kuliah_siakad_id', 30)->unique();
            $table->foreignId('program_studi_id')
                ->constrained()
                ->restrictOnDelete();
            $table->unsignedBigInteger('kurikulum_id')->nullable();
            $table->string('nama', 180);
            $table->string('jenis', 50)->nullable();
            $table->unsignedInteger('sks')->nullable();
            $table->unsignedInteger('sks_tatap_muka')->nullable();
            $table->unsignedInteger('sks_praktikum')->nullable();
            $table->unsignedInteger('sks_simulasi')->nullable();
            $table->timestamp('updated_at_siakad')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
