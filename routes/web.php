<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanPageController; // Controller untuk halaman karyawan
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KaryawanController as AdminKaryawanController;
use App\Http\Controllers\Admin\PayrollController as AdminPayrollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman landing atau publik
Route::get('/', function () {
    if (Auth::check()) {
        return redirect(RouteServiceProvider::HOME); // Akan mengarah ke '/dashboard'
    }
    return view('welcome');
})->name('welcome');

// Route `/dashboard` utama, akan mengarahkan berdasarkan role
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    if ($user->isAdmin()) { // Pastikan method isAdmin ada di model User
        return redirect()->route('admin.dashboard');
    } elseif ($user->isKaryawan()) { // Pastikan method isKaryawan ada di model User
        return redirect()->route('karyawan.dashboard'); // INI AKAN MENGARAHKAN KE KARYAWAN DASHBOARD
    } else {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('error', 'Role pengguna tidak dikenali atau tidak valid.');
    }
})->middleware(['auth', 'verified'])->name('dashboard'); // 'verified' opsional jika pakai email verification


// Route untuk profil pengguna (dari Breeze/Jetstream)
Route::middleware(['auth', 'verified'])->group(function () { // 'verified' opsional
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- GRUP ROUTE ADMIN ---
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin-area')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Route: admin.dashboard
    Route::resource('karyawan', AdminKaryawanController::class); // Routes: admin.karyawan.index, .create, .store, dll.
    Route::get('payroll/rekap-absensi', [AdminPayrollController::class, 'rekapAbsensi'])->name('payroll.rekap_absensi');
    Route::post('payroll/hitung-gaji', [AdminPayrollController::class, 'hitungGajiBulanan'])->name('payroll.hitung_gaji');
    Route::get('payroll/daftar-gaji', [AdminPayrollController::class, 'daftarGaji'])->name('payroll.daftar_gaji');
    Route::get('payroll/slip-gaji/{gaji}', [AdminPayrollController::class, 'cetakSlipGaji'])->name('payroll.cetak_slip');
});


// --- GRUP ROUTE KARYAWAN ---
Route::middleware(['auth', 'verified', 'karyawan'])->prefix('karyawan-area')->name('karyawan.')->group(function () {
    // Dashboard Karyawan
    Route::get('/dashboard', [KaryawanPageController::class, 'dashboard'])->name('dashboard'); // Route: karyawan.dashboard

    // Fitur Absensi Karyawan
    Route::post('presensi/masuk', [KaryawanPageController::class, 'presensiMasuk'])->name('presensi.masuk'); // Route: karyawan.presensi.masuk
    Route::post('presensi/pulang', [KaryawanPageController::class, 'presensiPulang'])->name('presensi.pulang'); // Route: karyawan.presensi.pulang
    Route::get('riwayat-absensi', [KaryawanPageController::class, 'riwayatAbsensi'])->name('riwayat.absensi'); // Route: karyawan.riwayat.absensi
});

// Memuat route untuk autentikasi (login, register, logout, dll.)
// Diasumsikan file ini dibuat oleh Laravel Breeze atau Fortify.
require __DIR__.'/auth.php';