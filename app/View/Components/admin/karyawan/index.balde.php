@extends('layouts.admin')

@section('title', 'Kelola Data Karyawan')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Data Karyawan</h1>
    <a href="{{ route('admin.karyawan.create') }}"
        class="mt-3 md:mt-0 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-plus mr-1"></i> Tambah Karyawan Baru
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        NIK
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Posisi
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Masuk
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($karyawans as $index => $karyawan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $karyawans->firstItem() + $index }} {{-- Nomor urut untuk paginasi --}}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            {{-- <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->user->name) }}&background=random" alt="{{ $karyawan->user->name }}">
                        </div> --}}
                        <div class="ml-0"> {{-- Jika tidak pakai avatar, ml-0 --}}
                            <div class="text-sm font-medium text-gray-900">
                                {{ $karyawan->user->name }}
                            </div>
                        </div>
    </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $karyawan->user->email }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $karyawan->nik ?? '-' }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $karyawan->posisi }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->translatedFormat('d M Y') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
        <a href="{{ route('admin.karyawan.show', $karyawan->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
            <i class="fas fa-eye"></i> Lihat
        </a>
        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan {{ $karyawan->user->name }}? Data absensi dan gaji terkait juga akan terhapus.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </form>
    </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
            Tidak ada data karyawan.
        </td>
    </tr>
    @endforelse
    </tbody>
    </table>
</div>
@if ($karyawans->hasPages())
<div class="px-6 py-3 bg-gray-50 border-t">
    {{ $karyawans->links() }} {{-- Menampilkan link paginasi --}}
</div>
@endif
</div>
@endsection

@push('styles')
{{-- Jika butuh FontAwesome untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush