@extends('layouts.admin')

@section('title', 'Edit Data Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Edit Data Karyawan: {{ $karyawan->user->name }}</h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Data User --}}
            <fieldset class="border p-4 rounded-md">
                <legend class="text-lg font-medium text-gray-700 px-2">Informasi Akun Login</legend>
                <div class="space-y-4 mt-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $karyawan->user->name) }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $karyawan->user->email) }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Isi jika ingin mengubah password">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
            </fieldset>

            {{-- Data Karyawan --}}
            <fieldset class="border p-4 rounded-md">
                <legend class="text-lg font-medium text-gray-700 px-2">Informasi Detail Karyawan</legend>
                <div class="space-y-4 mt-2">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK (Nomor Induk Karyawan)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $karyawan->nik) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('nik') border-red-500 @enderror">
                        @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('no_telepon') border-red-500 @enderror">
                        @error('no_telepon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="posisi" class="block text-sm font-medium text-gray-700">Posisi / Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="posisi" id="posisi" value="{{ old('posisi', $karyawan->posisi) }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('posisi') border-red-500 @enderror">
                        @error('posisi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk Kerja <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk->format('Y-m-d')) }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('tanggal_masuk') border-red-500 @enderror">
                        @error('tanggal_masuk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}" required step="0.01" min="0"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('gaji_pokok') border-red-500 @enderror">
                        @error('gaji_pokok') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('alamat') border-red-500 @enderror">{{ old('alamat', $karyawan->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.karyawan.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection