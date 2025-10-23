<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use Illuminate\Http\Request;

class TanamanController extends Controller
{
    public function getTanamanAktif()
    {
        // Ambil tanaman dengan status 'aktif' terbaru
        $tanaman = Tanaman::where('status', 'aktif')->latest()->first();

        if ($tanaman) {
            return response()->json([
                'nama_tanaman' => $tanaman->nama_tanaman,
                'hss' => $tanaman->hss,
                'hst' => $tanaman->hst,
                'anomali' => $tanaman->anomali_daur ?? 'Normal',
            ]);
        } else {
            return response()->json(['message' => 'Tidak ada tanaman aktif'], 404);
        }
    }
}
