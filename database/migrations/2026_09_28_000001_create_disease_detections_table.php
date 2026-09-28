<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_data_id')
                ->nullable()
                ->constrained('sensor_data')
                ->nullOnDelete();
            $table->foreignId('tanaman_id')
                ->nullable()
                ->constrained('tanaman')
                ->nullOnDelete();
            $table->string('device_id', 120)->nullable()->index();
            $table->string('disease', 100);
            $table->float('confidence');
            $table->string('image_path');
            $table->float('tds')->nullable();
            $table->float('suhu_air')->nullable();
            $table->float('suhu_udara')->nullable();
            $table->float('kelembaban')->nullable();
            $table->timestamp('detected_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_detections');
    }
};
