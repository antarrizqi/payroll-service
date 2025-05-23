<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Gaji;
use Carbon\Carbon;
// Hapus 'use Illuminate\Http\Request;' jika tidak digunakan di method index

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::count();
        $absenHariIniCount = Absensi::whereDate('tanggal', Carbon::today())
                                    ->distinct('karyawan_id')
                                    ->count();
        $gajiBulanIniProcessed = Gaji::where('bulan', Carbon::now()->month)
                                     ->where('tahun', Carbon::now()->year)
                                     ->exists();
        $karyawanBaru = Karyawan::with('user')
                                ->where('tanggal_masuk', '>=', Carbon::now()->subDays(30))
                                ->orderBy('tanggal_masuk', 'desc')
                                ->limit(5)
                                ->get();

        // PASTIKAN VIEW INI ADA: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', [
            'totalKaryawan' => $totalKaryawan,
            'absenHariIniCount' => $absenHariIniCount,
            'gajiBulanIniProcessed' => $gajiBulanIniProcessed,
            'karyawanBaru' => $karyawanBaru,
        ]);
    }
}