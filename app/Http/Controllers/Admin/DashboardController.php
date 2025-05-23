<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Gaji;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Total Karyawan Aktif
        $totalKaryawan = Karyawan::count(); // Asumsi semua karyawan di tabel adalah aktif

        // 2. Jumlah Karyawan yang Sudah Absen Masuk Hari Ini
        $today = Carbon::today();
        $absenHariIniCount = Absensi::whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk') // Hanya yang sudah jam masuk
            ->distinct('karyawan_id')   // Hitung unik per karyawan
            ->count();

        // 3. Status Pemrosesan Gaji Bulan Ini
        // Kita cek apakah ada record gaji untuk bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $gajiBulanIniProcessed = Gaji::where('bulan', $currentMonth)
            ->where('tahun', $currentYear)
            ->exists(); // Cukup cek apakah ada record, tidak perlu ambil semua data

        // 4. Daftar Karyawan yang Baru Bergabung (misalnya, dalam 30 hari terakhir)
        $karyawanBaru = Karyawan::with('user') // Eager load relasi user untuk mendapatkan nama
            ->where('tanggal_masuk', '>=', Carbon::now()->subDays(30))
            ->orderBy('tanggal_masuk', 'desc')
            ->limit(5) // Batasi jumlah yang ditampilkan di dashboard
            ->get();

        // Kirim data ke view
        return view('admin.dashboard', [
            'totalKaryawan' => $totalKaryawan,
            'absenHariIniCount' => $absenHariIniCount,
            'gajiBulanIniProcessed' => $gajiBulanIniProcessed,
            'karyawanBaru' => $karyawanBaru,
        ]);
    }
}
