<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider; // PENTING UNTUK REDIRECT
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Karyawan; // INI SUDAH BENAR


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Jika Anda punya field NIK, Posisi, dll. di form registrasi, tambahkan validasinya di sini
            // 'nik' => ['nullable', 'string', 'max:20', 'unique:karyawan,nik'],
            // 'posisi' => ['nullable', 'string', 'max:100'], // Jadikan nullable jika tidak diisi saat registrasi
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan', // Secara eksplisit set role 'karyawan' untuk user baru
        ]);

        // --- TAMBAHKAN BLOK INI UNTUK MEMBUAT DATA KARYAWAN ---
        try {
            Karyawan::create([
                'user_id' => $user->id,
                // Isi field lain dari request jika ada di form registrasi,
                // atau berikan nilai default, atau biarkan nullable jika di database memang boleh null
                // dan akan diisi nanti oleh admin.
                'nik' => $request->input('nik'), // Contoh: jika ada field 'nik' di form
                'posisi' => $request->input('posisi', 'Karyawan Baru'), // Contoh: ambil dari form atau default
                'tanggal_masuk' => now(), // Default tanggal masuk hari ini
                'gaji_pokok' => $request->input('gaji_pokok', 0), // Contoh: ambil dari form atau default 0
                'alamat' => $request->input('alamat'),
                'no_telepon' => $request->input('no_telepon'),
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error saat membuat Karyawan,
            // Anda mungkin ingin menghapus user yang baru dibuat untuk konsistensi data.
            // $user->delete();
            // Atau log error dan redirect dengan pesan error
            // Log::error("Gagal membuat record Karyawan untuk user ID {$user->id} saat registrasi: " . $e->getMessage());
            // return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan detail pendaftaran. Silakan coba lagi.');

            // Untuk development, biarkan errornya muncul agar kita tahu detailnya
            throw $e;
        }
        // --- AKHIR BLOK TAMBAHAN ---

        event(new Registered($user));

        Auth::login($user);

        // Gunakan RouteServiceProvider::HOME untuk redirect agar konsisten
        return redirect(RouteServiceProvider::HOME);
        // atau jika Anda sudah menamai route dashboard:
        // return redirect(route('dashboard'));
    }
}