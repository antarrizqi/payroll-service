@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Admin Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards (Contoh, sesuaikan dengan data yang relevan) -->
<div class="grid grid-cols-1 gap-5 mb-6 sm:grid-cols-2 lg:grid-cols-3">
    <div class="p-5 bg-white rounded-lg shadow">
        <h2 class="text-sm font-medium text-gray-500">Total Karyawan</h2>
        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalKaryawan ?? 0 }}</p>
    </div>
    <div class="p-5 bg-white rounded-lg shadow">
        <h2 class="text-sm font-medium text-gray-500">Absen Hari Ini</h2>
        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $absenHariIniCount ?? 0 }}</p>
         <a href="{{ route('admin.payroll.rekap_absensi', ['tanggal' => now()->format('Y-m-d')]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Lihat Detail</a>
    </div>
    <div class="p-5 bg-white rounded-lg shadow">
        <h2 class="text-sm font-medium text-gray-500">Gaji Bulan Ini (Belum Diproses)</h2>
        {{-- Logika untuk menampilkan status penggajian bulan ini --}}
        <p class="mt-1 text-xl font-semibold text-gray-900">
            @if(isset($gajiBulanIniProcessed) && $gajiBulanIniProcessed)
                Sudah Diproses
            @else
                <a href="{{ route('admin.payroll.daftar_gaji') }}#hitung-gaji" class="text-indigo-600 hover:text-indigo-900">Proses Sekarang</a>
            @endif
        </p>
    </div>
</div>

<!-- Shortcut / Quick Actions -->
<div class="mb-6">
    <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
    <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('admin.karyawan.create') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
            <svg class="w-8 h-8 mb-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            <span class="font-medium text-gray-700">Tambah Karyawan Baru</span>
        </a>
        <a href="{{ route('admin.payroll.daftar_gaji') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
            <svg class="w-8 h-8 mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="font-medium text-gray-700">Lihat & Hitung Gaji</span>
        </a>
        <a href="{{ route('admin.payroll.rekap_absensi') }}" class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
             <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="font-medium text-gray-700">Lihat Rekap Absensi</span>
        </a>
    </div>
</div>

{{-- Anda bisa tambahkan chart atau tabel ringkasan lain di sini --}}
{{-- Contoh tabel karyawan yang baru bergabung --}}
<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900">Karyawan Baru Bergabung (30 Hari Terakhir)</h3>
    <div class="mt-4 overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Posisi</th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal Masuk</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($karyawanBaru ?? [] as $karyawan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $karyawan->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $karyawan->posisi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $karyawan->tanggal_masuk->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm text-center text-gray-500">Tidak ada karyawan baru dalam 30 hari terakhir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
{{-- Jika ada script khusus untuk dashboard --}}
@endpush