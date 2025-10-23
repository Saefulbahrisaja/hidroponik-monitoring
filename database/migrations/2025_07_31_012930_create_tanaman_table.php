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
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->string('nama_ilmiah')->nullable();
            $table->date('hst')->nullable(); // tanggal semai benih
            $table->date('hss')->nullable(); // tanggal tanam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};
