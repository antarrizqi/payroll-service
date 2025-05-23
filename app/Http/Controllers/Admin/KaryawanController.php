<?php

// app/Http/Controllers/Admin/KaryawanController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Untuk transaksi

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->paginate(10);
        // return view('admin.karyawan.index', compact('karyawans'));
        return response()->json($karyawans);
    }

    public function create()
    {
        // return view('admin.karyawan.create');
        return response()->json(['message' => 'Show create form.']); // Untuk API
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string|max:20|unique:karyawan,nik',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
            'posisi' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'karyawan',
            ]);

            $karyawan = Karyawan::create([
                'user_id' => $user->id,
                'nik' => $validatedData['nik'],
                'alamat' => $validatedData['alamat'],
                'no_telepon' => $validatedData['no_telepon'],
                'posisi' => $validatedData['posisi'],
                'tanggal_masuk' => $validatedData['tanggal_masuk'],
                'gaji_pokok' => $validatedData['gaji_pokok'],
            ]);

            DB::commit();
            // return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
            return response()->json(['message' => 'Karyawan berhasil ditambahkan.', 'karyawan' => $karyawan->load('user')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->withInput()->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menambahkan karyawan: ' . $e->getMessage()], 500);
        }
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load('user', 'absensi', 'gaji');
        // return view('admin.karyawan.show', compact('karyawan'));
        return response()->json($karyawan);
    }

    public function edit(Karyawan $karyawan)
    {
        $karyawan->load('user');
        // return view('admin.karyawan.edit', compact('karyawan'));
        return response()->json($karyawan); // Untuk API
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $karyawan->user_id,
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'nik' => 'nullable|string|max:20|unique:karyawan,nik,' . $karyawan->id,
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:15',
            'posisi' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ];
            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }
            $karyawan->user()->update($userData);

            $karyawan->update([
                'nik' => $validatedData['nik'],
                'alamat' => $validatedData['alamat'],
                'no_telepon' => $validatedData['no_telepon'],
                'posisi' => $validatedData['posisi'],
                'tanggal_masuk' => $validatedData['tanggal_masuk'],
                'gaji_pokok' => $validatedData['gaji_pokok'],
            ]);

            DB::commit();
            // return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
            return response()->json(['message' => 'Data karyawan berhasil diperbarui.', 'karyawan' => $karyawan->load('user')]);
        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->withInput()->with('error', 'Gagal memperbarui data karyawan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memperbarui data karyawan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        DB::beginTransaction();
        try {
            // User akan terhapus otomatis jika onDelete('cascade') di migration karyawan
            // Jika tidak, hapus user secara manual:
            // $karyawan->user()->delete();
            $karyawan->delete(); // Ini akan cascade ke absensi dan gaji jika onDelete('cascade')
            DB::commit();
            // return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
            return response()->json(['message' => 'Karyawan berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->with('error', 'Gagal menghapus karyawan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus karyawan: ' . $e->getMessage()], 500);
        }
    }
}