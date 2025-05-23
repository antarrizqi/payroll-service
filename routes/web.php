<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanPageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KaryawanController as AdminKaryawanController;
use App\Http\Controllers\Admin\PayrollController as AdminPayrollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(RouteServiceProvider::HOME);
    }
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isKaryawan()) {
        return redirect()->route('karyawan.dashboard');
    } else {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('error', 'Role pengguna tidak valid atau tidak dikenali.');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- GRUP ROUTE ADMIN ---
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin-area')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('karyawan', AdminKaryawanController::class);
    Route::get('payroll/rekap-absensi', [AdminPayrollController::class, 'rekapAbsensi'])->name('payroll.rekap_absensi');
    Route::post('payroll/hitung-gaji', [AdminPayrollController::class, 'hitungGajiBulanan'])->name('payroll.hitung_gaji');
    Route::get('payroll/daftar-gaji', [AdminPayrollController::class, 'daftarGaji'])->name('payroll.daftar_gaji');
    Route::get('payroll/slip-gaji/{gaji}', [AdminPayrollController::class, 'cetakSlipGaji'])->name('payroll.cetak_slip');
});


// --- GRUP ROUTE KARYAWAN ---
Route::middleware(['auth', 'verified', 'karyawan'])->prefix('karyawan-area')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KaryawanPageController::class, 'dashboard'])->name('dashboard');
    Route::post('presensi/masuk', [KaryawanPageController::class, 'presensiMasuk'])->name('presensi.masuk');
    Route::post('presensi/pulang', [KaryawanPageController::class, 'presensiPulang'])->name('presensi.pulang');
    Route::get('riwayat-absensi', [KaryawanPageController::class, 'riwayatAbsensi'])->name('riwayat.absensi');
});

require __DIR__.'/auth.php';