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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->text('detail');
            $table->text('ingredients');
            $table->string('durasi');
            $table->string('kategori');
            $table->string('jenis_hidangan');
            $table->string('estimasi_waktu');
            $table->string('tingkat_kesulitan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
