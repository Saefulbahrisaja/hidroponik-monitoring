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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('suhu');
            $table->float('kelembaban');
            $table->float('suhuudara');
            $table->float('ph')->nullable();
            $table->float('tds')->nullable();
            $table->tinyInteger('pompa_air')->nullable();
            $table->tinyInteger('pompa_nutrisi')->nullable();
            $table->float('level_air')->nullable();
            $table->float('air_min')->nullable();

            // ✅ Tambahkan relasi tanaman di sini
            $table->foreignId('tanaman_id')
                  ->nullable()
                  ->constrained('tanaman')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
