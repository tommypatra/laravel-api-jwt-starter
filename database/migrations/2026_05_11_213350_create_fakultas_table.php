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
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fakultas_siakad_id')->nullable()->unique();
            $table->string('nama_fakultas', 180);
            $table->string('fakultas_singkatan', 50)->nullable()->unique();
            $table->boolean('is_aktif')->default(1);
            $table->timestamp('updated_at_siakad')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fakultas');
    }
};
