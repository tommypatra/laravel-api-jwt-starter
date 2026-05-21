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
        Schema::create('jadwal_mengajars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distribusi_mengajar_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('hari_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('slot_mulai_id')
                ->constrained('slot_waktus')
                ->restrictOnDelete();

            $table->foreignId('slot_selesai_id')
                ->constrained('slot_waktus')
                ->restrictOnDelete();

            $table->unsignedBigInteger('ruangan_siakad_id')->index();
            $table->unique([
                'hari_id',
                'slot_mulai_id',
                'slot_selesai_id',
                'ruangan_siakad_id',
            ], 'jadwal_mengajar_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mengajars');
    }
};
