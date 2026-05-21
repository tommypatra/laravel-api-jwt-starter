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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangan_siakad_id')->unique();
            $table->string('lokasi_ruang', 50)->nullable();
            $table->string('nama_ruangan', 180);
            $table->unsignedInteger('lantai')->nullable();
            $table->boolean('is_aktif')->nullable();
            $table->timestamp('updated_at_siakad')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
