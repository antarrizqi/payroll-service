@extends('layouts.admin')

@section('title', 'Rekap Absensi Karyawan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Rekap Absensi Karyawan</h1>
</div>

{{-- Filter Periode --}}
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <form action="{{ route('admin.payroll.rekap_absensi') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                <select name="bulan" id="bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <input type="number" name="tahun" id="tahun" value="{{ $tahun }}" min="2000" max="{{ date('Y') + 1 }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Tampilkan Rekap
                </button>
            </div>
        </div>
    </form>
</div>

@if (!empty($rekap_absensi) && count($rekap_absensi) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Karyawan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIK
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hadir
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Izin
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sakit
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alpha
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Hari Kerja Efektif (Asumsi)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $firstDayOfMonth = \Carbon\Carbon::create($tahun, $bulan, 1);
                        $totalWorkingDays = 0;
                        for ($day = 1; $day <= $firstDayOfMonth->daysInMonth; $day++) {
                            $currentDate = \Carbon\Carbon::create($tahun, $bulan, $day);
                            // Asumsi hari kerja Senin-Jumat, bisa disesuaikan
                            if ($currentDate->isWeekday()) {
                                $totalWorkingDays++;
                            }
                        }
                    @endphp
                    @foreach ($rekap_absensi as $index => $rekap)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $rekap['karyawan_nama'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $rekap['karyawan_nik'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-600 font-semibold">
                                {{ $rekap['total_hadir'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-yellow-600 font-semibold">
                                {{ $rekap['total_izin'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-orange-600 font-semibold">
                                {{ $rekap['total_sakit'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-red-600 font-semibold">
                                {{ $rekap['total_tanpa_keterangan'] }}
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $totalWorkingDays }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white shadow-md rounded-lg p-6 text-center">
        <p class="text-gray-500">Tidak ada data absensi untuk periode {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}.</p>
        <p class="text-gray-400 text-sm mt-1">Atau silakan pilih periode lain dan klik "Tampilkan Rekap".</p>
    </div>
@endif
@endsection