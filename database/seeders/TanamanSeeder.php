<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tanaman;
use Carbon\Carbon;

class TanamanSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        Tanaman::create([
            'nama_tanaman' => 'Selada',
            'nama_ilmiah'  => 'Lactuca sativa',
            'hst'          => Carbon::now()->subDays(10)->format('Y-m-d'), // 10 hari lalu disemai
            'hss'          => Carbon::now()->subDays(3)->format('Y-m-d'),  // 3 hari lalu ditanam
        ]);

        Tanaman::create([
            'nama_tanaman' => 'Kangkung',
            'nama_ilmiah'  => 'Ipomoea aquatica',
            'hst'          => Carbon::now()->subDays(8)->format('Y-m-d'),  // 8 hari lalu disemai
            'hss'          => Carbon::now()->subDays(2)->format('Y-m-d'),  // 2 hari lalu ditanam
        ]);

        Tanaman::create([
            'nama_tanaman' => 'Bayam Hijau',
            'nama_ilmiah'  => 'Amaranthus tricolor',
            'hst'          => Carbon::now()->subDays(12)->format('Y-m-d'),
            'hss'          => Carbon::now()->subDays(5)->format('Y-m-d'),
        ]);
    }
}
