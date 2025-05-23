<?php
// routes/web.php
use App\Http\Controllers\KaryawanPageController; // Pastikan sudah di-import

// ... (route lainnya) ...

Route::middleware(['auth', 'karyawan'])->prefix('karyawan-area')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanPageController::class, 'dashboard'])->name('dashboard');
    Route::post('/presensi/masuk', [KaryawanPageController::class, 'presensiMasuk'])->name('presensi.masuk');
    Route::post('/presensi/pulang', [KaryawanPageController::class, 'presensiPulang'])->name('presensi.pulang');
    Route::get('/riwayat-absensi', [KaryawanPageController::class, 'riwayatAbsensi'])->name('riwayat.absensi'); // Ini dia
});
