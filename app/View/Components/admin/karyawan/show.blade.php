@extends('layouts.admin')

@section('title', 'Detail Karyawan: ' . $karyawan->user->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-900">Detail Karyawan: {{ $karyawan->user->name }}</h1>
    <a href="{{ route('admin.karyawan.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Kembali ke Daftar Karyawan</a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Informasi Karyawan
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Detail pribadi dan pekerjaan.
        </p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->user->name }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Alamat Email</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->user->email }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">NIK</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->nik ?? '-' }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->no_telepon ?? '-' }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Posisi</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->posisi }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Tanggal Masuk</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->tanggal_masuk->translatedFormat('d F Y') }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Gaji Pokok</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Rp {{ number_format($karyawan->gaji_pokok, 2, ',', '.') }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->alamat ?? '-' }}</dd>
            </div>
             <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Akun Dibuat Pada</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->user->created_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Data Terakhir Diperbarui</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $karyawan->updated_at->translatedFormat('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Edit Data Karyawan
        </a>
    </div>
</div>

{{-- Tambahan: Menampilkan Riwayat Absensi Terakhir (Contoh) --}}
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900">Riwayat Absensi Terakhir (5 Hari)</h3>
    <div class="mt-4 overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($karyawan->absensi()->latest()->take(5)->get() as $absensi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absensi->tanggal->translatedFormat('d M Y, l') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($absensi->status == 'hadir') bg-green-100 text-green-800
                                @elseif($absensi->status == 'izin') bg-yellow-100 text-yellow-800
                                @elseif($absensi->status == 'sakit') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($absensi->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection