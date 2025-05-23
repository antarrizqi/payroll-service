<?php

// app/Http/Controllers/Admin/PayrollController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    // Lihat Rekap Absensi Semua Karyawan (bisa per bulan/periode)
    public function rekapAbsensi(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $absensi = Absensi::with('karyawan.user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('karyawan_id'); // Group by karyawan untuk rekap

        $rekap = [];
        foreach ($absensi as $karyawanId => $dataAbsensi) {
            $karyawan = Karyawan::find($karyawanId);
            if ($karyawan) {
                $rekap[] = [
                    'karyawan_nama' => $karyawan->user->name,
                    'karyawan_nik' => $karyawan->nik,
                    'total_hadir' => $dataAbsensi->where('status', 'hadir')->count(),
                    'total_izin' => $dataAbsensi->where('status', 'izin')->count(),
                    'total_sakit' => $dataAbsensi->where('status', 'sakit')->count(),
                    'total_tanpa_keterangan' => $dataAbsensi->where('status', 'tanpa_keterangan')->count(),
                    'detail_absensi' => $dataAbsensi // Opsional, jika mau detail
                ];
            }
        }

        // return view('admin.payroll.rekap_absensi', compact('rekap', 'bulan', 'tahun'));
        return response()->json(['rekap_absensi' => $rekap, 'bulan' => $bulan, 'tahun' => $tahun]);
    }


    // Halaman untuk menampilkan daftar gaji yang sudah dihitung
    public function daftarGaji(Request $request)
    {
        $gaji = Gaji::with('karyawan.user')
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->paginate(15);
        // return view('admin.payroll.daftar_gaji', compact('gaji'));
        return response()->json($gaji);
    }


    // Proses Hitung Gaji Bulanan (untuk semua karyawan atau per karyawan)
    public function hitungGajiBulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|digits:4',
            'karyawan_id' => 'nullable|exists:karyawan,id', // Opsional, jika mau hitung per karyawan
            'potongan_per_hari_alpha' => 'required|numeric|min:0', // Contoh input potongan
            'potongan_per_hari_izin' => 'required|numeric|min:0',  // Contoh input potongan
            'potongan_per_hari_sakit' => 'required|numeric|min:0'  // Contoh input potongan
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $karyawanId = $request->input('karyawan_id');

        $potonganAlpha = $request->input('potongan_per_hari_alpha');
        $potonganIzin = $request->input('potongan_per_hari_izin');
        $potonganSakit = $request->input('potongan_per_hari_sakit');

        $query = Karyawan::query();
        if ($karyawanId) {
            $query->where('id', $karyawanId);
        }
        $karyawans = $query->get();

        $hasilPerhitungan = [];
        DB::beginTransaction();
        try {
            foreach ($karyawans as $karyawan) {
                $absensiBulanIni = Absensi::where('karyawan_id', $karyawan->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->get();

                $totalHadir = $absensiBulanIni->where('status', 'hadir')->count();
                $totalIzin = $absensiBulanIni->where('status', 'izin')->count();
                $totalSakit = $absensiBulanIni->where('status', 'sakit')->count();
                $totalTanpaKeterangan = $absensiBulanIni->where('status', 'tanpa_keterangan')->count();

                // Logika Potongan (CONTOH SEDERHANA)
                // Anda perlu menyesuaikan ini dengan kebijakan perusahaan
                $potongan = ($totalTanpaKeterangan * $potonganAlpha) +
                            ($totalIzin * $potonganIzin) +
                            ($totalSakit * $potonganSakit);


                $gajiPokok = $karyawan->gaji_pokok;
                $gajiBersih = $gajiPokok - $potongan;

                // Simpan atau update data gaji
                $gajiRecord = Gaji::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'total_hadir' => $totalHadir,
                        'total_izin' => $totalIzin,
                        'total_sakit' => $totalSakit,
                        'total_tanpa_keterangan' => $totalTanpaKeterangan,
                        'gaji_pokok' => $gajiPokok,
                        'potongan' => $potongan,
                        'gaji_bersih' => $gajiBersih,
                        'keterangan_gaji' => 'Perhitungan gaji bulan ' . $bulan . '/' . $tahun,
                        'tanggal_pembayaran' => null, // Diisi saat pembayaran
                    ]
                );
                $hasilPerhitungan[] = $gajiRecord->load('karyawan.user');
            }
            DB::commit();
            // return redirect()->route('admin.payroll.daftar_gaji')->with('success', 'Gaji berhasil dihitung dan disimpan.');
            return response()->json(['message' => 'Gaji berhasil dihitung dan disimpan.', 'data' => $hasilPerhitungan]);

        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->with('error', 'Gagal menghitung gaji: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghitung gaji: ' . $e->getMessage()], 500);
        }
    }

    // Cetak Slip Gaji Sederhana (Menyiapkan data untuk view/PDF)
    public function cetakSlipGaji(Gaji $gaji) // Menggunakan Route Model Binding
    {
        $gaji->load('karyawan.user'); // Load relasi yang dibutuhkan

        // Logika untuk generate PDF atau HTML view slip gaji
        // Contoh: return view('admin.payroll.slip_gaji_pdf', compact('gaji'));
        // Untuk API, cukup kembalikan data JSON
        return response()->json($gaji);
    }
}
