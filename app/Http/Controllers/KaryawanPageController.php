<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
// Karyawan model tidak perlu di-import lagi di sini jika tidak digunakan secara langsung,
// karena kita mengambilnya dari $user->karyawan
// use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KaryawanPageController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Sesi tidak valid atau detail karyawan tidak ditemukan.');
        }
        $karyawan = $user->karyawan;

        $today = Carbon::today();
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        $riwayatAbsensiTerbaru = Absensi::where('karyawan_id', $karyawan->id)
                                     ->orderBy('tanggal', 'desc')
                                     ->orderBy('created_at', 'desc')
                                     ->take(5)
                                     ->get();

        // PASTIKAN VIEW INI ADA: resources/views/karyawan/dashboard.blade.php
        return view('karyawan.dashboard', compact(
            'karyawan',
            'absensiHariIni',
            'riwayatAbsensiTerbaru'
        ));
    }

    public function presensiMasuk(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Aksi tidak diizinkan.');
        }
        $karyawan = $user->karyawan;
        $today = Carbon::today();
        $now = Carbon::now();
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        if ($absensiHariIni && $absensiHariIni->jam_masuk) {
            return redirect()->route('karyawan.dashboard')->with('warning', 'Anda sudah melakukan presensi masuk hari ini.');
        }
        if ($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit', 'tanpa_keterangan'])) {
             return redirect()->route('karyawan.dashboard')->with('warning', 'Anda tercatat ' . $absensiHariIni->status . ' hari ini. Tidak bisa presensi masuk.');
        }

        if ($absensiHariIni) {
            $absensiHariIni->update(['jam_masuk' => $now->format('H:i:s'), 'status' => 'hadir']);
        } else {
            Absensi::create([
                'karyawan_id' => $karyawan->id,
                'tanggal' => $today,
                'jam_masuk' => $now->format('H:i:s'),
                'status' => 'hadir',
                'keterangan' => 'Presensi masuk otomatis',
            ]);
        }
        return redirect()->route('karyawan.dashboard')->with('success', 'Presensi masuk berhasil dicatat.');
    }

    public function presensiPulang(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Aksi tidak diizinkan.');
        }
        $karyawan = $user->karyawan;
        $today = Carbon::today();
        $now = Carbon::now();
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        if (!$absensiHariIni || !$absensiHariIni->jam_masuk) {
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda belum presensi masuk hari ini.');
        }
        if ($absensiHariIni->jam_pulang) {
            return redirect()->route('karyawan.dashboard')->with('warning', 'Anda sudah presensi pulang hari ini.');
        }
        if ($absensiHariIni->status !== 'hadir') {
             return redirect()->route('karyawan.dashboard')->with('warning', 'Status absensi Anda bukan "hadir", tidak bisa presensi pulang.');
        }

        $absensiHariIni->update(['jam_pulang' => $now->format('H:i:s')]);
        return redirect()->route('karyawan.dashboard')->with('success', 'Presensi pulang berhasil dicatat.');
    }

    public function riwayatAbsensi(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid.');
        }
        $karyawan = $user->karyawan;
        $query = Absensi::where('karyawan_id', $karyawan->id);

        if ($request->filled('bulan') && is_numeric($request->input('bulan'))) {
            $query->whereMonth('tanggal', $request->input('bulan'));
        }
        if ($request->filled('tahun') && is_numeric($request->input('tahun'))) {
            $query->whereYear('tanggal', $request->input('tahun'));
        }

        $riwayatAbsensiKaryawan = $query->orderBy('tanggal', 'desc')
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(15)
                                       ->withQueryString();
        // PASTIKAN VIEW INI ADA: resources/views/karyawan/riwayat_absensi.blade.php
        return view('karyawan.riwayat_absensi', compact('karyawan', 'riwayatAbsensiKaryawan'));
    }
}