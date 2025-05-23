<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan; // Import model
// use App\Models\User; // Jika perlu membuat user dulu

class TestKaryawanModel extends Command
{
    protected $signature = 'test:karyawan-model';
    protected $description = 'Test if Karyawan model can be resolved and used';

    public function handle()
    {
        $this->info('Mencoba me-resolve class Karyawan...');
        try {
            // Coba buat instance (tidak perlu disimpan ke DB untuk tes ini)
            $karyawanInstance = new Karyawan();
            $this->info('Class App\Models\Karyawan berhasil di-resolve dan di-instantiate.');

            // Opsional: Coba query sederhana jika tabel sudah ada
            // $karyawanCount = Karyawan::count();
            // $this->info("Jumlah record Karyawan di database: " . $karyawanCount);

            // Opsional: Coba buat record dummy jika tabel sudah ada dan fillable benar
            // $user = User::first(); // Ambil user pertama sebagai contoh
            // if ($user) {
            //     $newKaryawan = Karyawan::create([
            //         'user_id' => $user->id,
            //         'posisi' => 'Tester Model',
            //         'tanggal_masuk' => now(),
            //         'gaji_pokok' => 100000
            //     ]);
            //     $this->info("Berhasil membuat Karyawan dummy dengan ID: " . $newKaryawan->id);
            //     $newKaryawan->delete(); // Hapus lagi agar tidak mengotori DB
            // } else {
            //     $this->warn("Tidak ada user untuk membuat Karyawan dummy.");
            // }

        } catch (\Throwable $e) {
            $this->error('ERROR SAAT MENCOBA MODEL KARYAWAN:');
            $this->error($e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            // $this->error("Trace: \n" . $e->getTraceAsString()); // Bisa sangat panjang
        }
        return 0;
    }
}