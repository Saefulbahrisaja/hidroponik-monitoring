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
    Schema::table('sensor_data', function (Blueprint $table) {
        $table->boolean('pompa_air')->default(false);
        $table->boolean('pompa_nutrisi')->default(false);
        $table->float('level_air')->nullable();
        $table->float('air_min')->nullable(); // bisa redundant, tapi disimpan untuk audit jika nilai berubah-ubah
    });
}

public function down(): void
{
    Schema::table('sensor_data', function (Blueprint $table) {
        $table->dropColumn(['pompa_air', 'pompa_nutrisi', 'level_air', 'air_min']);
    });
}
};
