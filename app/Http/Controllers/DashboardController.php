<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SensorData;

class DashboardController extends Controller
{
    public function index()
    {
        $data = SensorData::latest()->take(50)->get();
        return view('dashboard', compact('data'));
    }
}
