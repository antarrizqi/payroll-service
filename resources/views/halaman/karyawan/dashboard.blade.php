@extends('layouts.karyawan') {{-- Atau layouts.app jika pakai Breeze dan disesuaikan --}}

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard Absensi</h1>
    <p class="text-gray-600">Selamat datang, {{ $karyawan->user->name ?? Auth::user()->name }}!</p>
</div>

<div class="p-6 mb-6 bg-white rounded-lg shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Informasi Anda</h2>
            <div class="mt-2 space-y-1 text-sm text-gray-600">
                <p><strong>Nama:</strong> {{ $karyawan->user->name ?? '-' }}</p>
                <p><strong>NIK:</strong> {{ $karyawan->nik ?? '-' }}</p>
                <p><strong>Posisi:</strong> {{ $karyawan->posisi ?? '-' }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Presensi Hari Ini</h2>
            @if ($absensiHariIni)
                <div class="mt-2 space-y-1 text-sm">
                    <p class="text-gray-700"><strong>Status:</strong>
                        <span class="font-semibold
                            @if($absensiHariIni->status == 'hadir') text-green-600
                            @elseif(in_array($absensiHariIni->status, ['izin', 'sakit'])) text-yellow-600
                            @else text-red-600 @endif">
                            {{ ucfirst($absensiHariIni->status) }}
                        </span>
                    </p>
                    <p class="text-gray-700"><strong>Jam Masuk:</strong> {{ $absensiHariIni->jam_masuk ? \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') : '-' }}</p>
                    <p class="text-gray-700"><strong>Jam Pulang:</strong> {{ $absensiHariIni->jam_pulang ? \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') : '-' }}</p>
                     @if($absensiHariIni->keterangan && $absensiHariIni->status != 'hadir')
                     <p class="text-gray-700"><strong>Keterangan:</strong> {{ $absensiHariIni->keterangan }}</p>
                     @endif
                </div>
            @else
                <p class="mt-2 text-sm text-gray-500">Anda belum melakukan presensi hari ini.</p>
            @endif

            <div class="mt-4 flex space-x-3">
                <form action="{{ route('karyawan.presensi.masuk') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                            {{ ($absensiHariIni && $absensiHariIni->jam_masuk) || ($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit'])) ? 'disabled' : '' }}>
                        Presensi Masuk
                    </button>
                </form>
                <form action="{{ route('karyawan.presensi.pulang') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                            {{ (!$absensiHariIni || !$absensiHariIni->jam_masuk || $absensiHariIni->jam_pulang || ($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit']))) ? 'disabled' : '' }}>
                        Presensi Pulang
                    </button>
                </form>
            </div>
             @if ($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit']))
                <p class="mt-3 text-xs text-orange-600">Anda tercatat {{ $absensiHariIni->status }} hari ini. Hubungi admin jika ingin mengubah status menjadi hadir.</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        {{-- Judul diubah sedikit agar sesuai dengan data yang ditampilkan (5 hari terakhir, bukan semua riwayat) --}}
        <h2 class="text-lg font-semibold text-gray-800">Riwayat Absensi Terbaru (5 Hari Terakhir)</h2>
        <a href="{{ route('karyawan.riwayat.absensi') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
            Lihat Semua Riwayat →
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    {{-- Nama kolom disesuaikan dengan view riwayat_absensi.blade.php untuk konsistensi --}}
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($riwayatAbsensiTerbaru as $absensi) {{-- Tetap menggunakan $riwayatAbsensiTerbaru --}}
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absensi->tanggal->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absensi->tanggal->translatedFormat('l') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($absensi->status == 'hadir') bg-green-100 text-green-800
                                @elseif($absensi->status == 'izin') bg-yellow-100 text-yellow-800
                                @elseif($absensi->status == 'sakit') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($absensi->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absensi->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">Belum ada riwayat absensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Bagian paginasi dihapus karena $riwayatAbsensiTerbaru bukan objek Paginator --}}
    {{--
    @if($riwayatAbsensi->hasPages())  // <--- INI BAGIAN YANG DIHAPUS/DIKOMENTARI
        <div class="mt-4">
            {{ $riwayatAbsensi->links() }}
        </div>
    @endif
    --}}
</div>
@endsection
