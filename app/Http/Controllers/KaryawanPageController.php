<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan; // Pastikan model Karyawan di-import jika belum ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KaryawanPageController extends Controller
{
    /**
     * Menampilkan dashboard karyawan.
     * Mengirim data karyawan, absensi hari ini, dan riwayat absensi terbaru.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Validasi awal: pastikan user login dan memiliki data karyawan terkait
        if (!$user || !$user->karyawan) {
            // Jika tidak valid, logout dan redirect ke login dengan pesan error
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Sesi tidak valid atau detail karyawan tidak ditemukan. Silakan login kembali.');
        }

        $karyawan = $user->karyawan; // Ambil data karyawan dari relasi

        $today = Carbon::today();
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayatAbsensiTerbaru = Absensi::where('karyawan_id', $karyawan->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5) // Ambil 5 data terbaru untuk ringkasan di dashboard
            ->get();

        // Mengirim data ke view 'karyawan.dashboard'
        return view('halaman.karyawan.dashboard', compact(
            'karyawan',
            'absensiHariIni',
            'riwayatAbsensiTerbaru'
        ));
    }

    /**
     * Menangani aksi presensi masuk karyawan.
     */
    public function presensiMasuk(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Aksi tidak diizinkan. Data karyawan tidak ditemukan.');
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

        // Cegah presensi masuk jika statusnya izin, sakit, atau tanpa keterangan
        if ($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit', 'tanpa_keterangan'])) {
            return redirect()->route('karyawan.dashboard')->with('warning', 'Anda tercatat ' . $absensiHariIni->status . ' hari ini. Tidak bisa melakukan presensi masuk.');
        }

        if ($absensiHariIni) { // Jika ada record (misalnya 'tanpa keterangan' default) tapi belum jam masuk
            $absensiHariIni->update([
                'jam_masuk' => $now->format('H:i:s'),
                'status' => 'hadir',
            ]);
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

    /**
     * Menangani aksi presensi pulang karyawan.
     */
    public function presensiPulang(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Aksi tidak diizinkan. Data karyawan tidak ditemukan.');
        }
        $karyawan = $user->karyawan;

        $today = Carbon::today();
        $now = Carbon::now();

        $absensiHariIni = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensiHariIni || !$absensiHariIni->jam_masuk) {
            return redirect()->route('karyawan.dashboard')->with('error', 'Anda belum melakukan presensi masuk hari ini untuk bisa presensi pulang.');
        }

        if ($absensiHariIni->jam_pulang) {
            return redirect()->route('karyawan.dashboard')->with('warning', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        // Hanya bisa presensi pulang jika statusnya 'hadir'
        if ($absensiHariIni->status !== 'hadir') {
            return redirect()->route('karyawan.dashboard')->with('warning', 'Status absensi Anda bukan "hadir" (' . $absensiHariIni->status . '), tidak bisa melakukan presensi pulang.');
        }

        $absensiHariIni->update([
            'jam_pulang' => $now->format('H:i:s'),
        ]);

        return redirect()->route('karyawan.dashboard')->with('success', 'Presensi pulang berhasil dicatat.');
    }

    /**
     * Menampilkan riwayat absensi pribadi karyawan dengan paginasi dan filter.
     */
    public function riwayatAbsensi(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->karyawan) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid atau detail karyawan tidak ditemukan.');
        }
        $karyawan = $user->karyawan;

        $query = Absensi::where('karyawan_id', $karyawan->id);

        // Filter berdasarkan bulan
        if ($request->filled('bulan') && is_numeric($request->input('bulan'))) {
            $query->whereMonth('tanggal', $request->input('bulan'));
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun') && is_numeric($request->input('tahun'))) {
            $query->whereYear('tanggal', $request->input('tahun'));
        }

        $riwayatAbsensiKaryawan = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15) // Jumlah item per halaman
            ->withQueryString(); // Agar parameter filter tetap ada di link paginasi

        return view('karyawan.riwayat_absensi', compact('karyawan', 'riwayatAbsensiKaryawan'));
    }
}
