@extends('layouts.admin')

@section('title', 'Penggajian Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Penggajian Karyawan</h1>
</div>

{{-- Form Hitung Gaji Bulanan --}}
<div class="bg-white shadow-md rounded-lg p-6 mb-8" id="hitung-gaji">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Hitung Gaji Bulanan</h2>
    <form action="{{ route('admin.payroll.hitung_gaji') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghitung gaji untuk periode ini? Proses ini akan membuat atau memperbarui data gaji yang sudah ada.');">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            <div>
                <label for="bulan_hitung" class="block text-sm font-medium text-gray-700">Bulan <span class="text-red-500">*</span></label>
                <select name="bulan" id="bulan_hitung" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('bulan', now()->month) == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                @error('bulan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tahun_hitung" class="block text-sm font-medium text-gray-700">Tahun <span class="text-red-500">*</span></label>
                <input type="number" name="tahun" id="tahun_hitung" value="{{ old('tahun', now()->year) }}" required min="2000" max="{{ date('Y') + 1 }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('tahun') border-red-500 @enderror">
                @error('tahun') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            {{-- Input untuk Potongan (CONTOH) --}}
            <div>
                <label for="potongan_per_hari_alpha" class="block text-sm font-medium text-gray-700">Potongan Alpha/Hari (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="potongan_per_hari_alpha" id="potongan_per_hari_alpha" value="{{ old('potongan_per_hari_alpha', 50000) }}" required step="1000" min="0"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('potongan_per_hari_alpha') border-red-500 @enderror">
                @error('potongan_per_hari_alpha') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
             <div>
                <label for="potongan_per_hari_izin" class="block text-sm font-medium text-gray-700">Potongan Izin/Hari (Rp)</label>
                <input type="number" name="potongan_per_hari_izin" id="potongan_per_hari_izin" value="{{ old('potongan_per_hari_izin', 0) }}" step="1000" min="0"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('potongan_per_hari_izin') border-red-500 @enderror">
                 @error('potongan_per_hari_izin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
             <div>
                <label for="potongan_per_hari_sakit" class="block text-sm font-medium text-gray-700">Potongan Sakit/Hari (Rp)</label>
                <input type="number" name="potongan_per_hari_sakit" id="potongan_per_hari_sakit" value="{{ old('potongan_per_hari_sakit', 0) }}" step="1000" min="0"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('potongan_per_hari_sakit') border-red-500 @enderror">
                 @error('potongan_per_hari_sakit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            {{-- Opsional: Hitung untuk karyawan tertentu --}}
            {{-- <div>
                <label for="karyawan_id_hitung" class="block text-sm font-medium text-gray-700">Karyawan (Opsional)</label>
                <select name="karyawan_id" id="karyawan_id_hitung"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">-- Semua Karyawan --</option>
                    @foreach (\App\Models\Karyawan::with('user')->get() as $k_opt)
                        <option value="{{ $k_opt->id }}" {{ old('karyawan_id') == $k_opt->id ? 'selected' : '' }}>
                            {{ $k_opt->user->name }} ({{ $k_opt->nik }})
                        </option>
                    @endforeach
                </select>
            </div> --}}
            <div class="lg:col-span-1 flex items-end">
                <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-calculator mr-1"></i> Hitung & Simpan Gaji
                </button>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500">Catatan: Menghitung gaji akan membuat data gaji baru atau memperbarui data yang sudah ada untuk periode yang dipilih.</p>
    </form>
</div>

{{-- Daftar Gaji yang Sudah Dihitung --}}
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-800">Riwayat Perhitungan Gaji</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Pokok</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Potongan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Bersih</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($gajiRecords as $gaji)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::create()->month($gaji->bulan)->translatedFormat('F') }} {{ $gaji->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $gaji->karyawan->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                            Rp {{ number_format($gaji->potongan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $gaji->tanggal_pembayaran ? \Carbon\Carbon::parse($gaji->tanggal_pembayaran)->translatedFormat('d M Y') : 'Belum Dibayar' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.payroll.cetak_slip', $gaji->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900" title="Cetak Slip">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                            {{-- Tombol untuk menandai sudah dibayar (implementasi terpisah) --}}
                            {{-- <form action="{{ route('admin.payroll.tandai_dibayar', $gaji->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:text-green-900" title="Tandai Sudah Dibayar">
                                    <i class="fas fa-check-circle"></i> Bayar
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data gaji yang dihitung.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($gajiRecords->hasPages())
        <div class="px-6 py-3 bg-gray-50 border-t">
            {{ $gajiRecords->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush