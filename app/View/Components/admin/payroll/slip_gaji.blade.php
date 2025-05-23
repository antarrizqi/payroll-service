<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->karyawan->user->name }} - {{ \Carbon\Carbon::create()->month($gaji->bulan)->translatedFormat('F') }} {{ $gaji->tahun }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif; /* Font standar untuk print */
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important; /* Chrome, Safari */
            color-adjust: exact !important; /* Firefox */
        }
        .container {
            width: 800px; /* Sesuaikan lebar slip */
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }
        .company-details { text-align: left; margin-bottom: 15px;} /* Tambahkan ini */
        .company-details p { margin: 2px 0; font-size: 12px; }

        .employee-details, .salary-details {
            margin-bottom: 20px;
        }
        .employee-details h2, .salary-details h2 {
            font-size: 16px;
            color: #444;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 180px 1fr; /* Lebar label dan nilai */
            gap: 5px 15px; /* Jarak antar baris dan kolom */
            font-size: 13px;
        }
        .detail-grid dt {
            font-weight: bold;
            color: #555;
        }
        .detail-grid dd {
            margin: 0;
            color: #333;
        }
        .salary-summary {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .salary-summary .detail-grid dt {
            font-size: 14px;
        }
        .salary-summary .detail-grid dd {
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .print-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
            .container {
                border: none;
                margin: 0;
                width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Tambahkan logo perusahaan jika ada --}}
            {{-- <img src="/path/to/your/logo.png" alt="Logo Perusahaan" style="max-height: 60px; margin-bottom: 10px;"> --}}
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>Periode: {{ \Carbon\Carbon::create()->month($gaji->bulan)->translatedFormat('F') }} {{ $gaji->tahun }}</p>
        </div>

        <div class="company-details no-print"> {{-- Detail perusahaan bisa di-hide saat print jika tidak perlu --}}
            <p><strong>{{ config('app.company_name', 'Nama Perusahaan Anda') }}</strong></p>
            <p>{{ config('app.company_address', 'Alamat Perusahaan Anda, Kota, Kode Pos') }}</p>
            <p>Telepon: {{ config('app.company_phone', '(021) 123-4567') }}</p>
        </div>


        <div class="employee-details">
            <h2>Detail Karyawan</h2>
            <dl class="detail-grid">
                <dt>Nama Karyawan:</dt>
                <dd>{{ $gaji->karyawan->user->name }}</dd>
                <dt>NIK:</dt>
                <dd>{{ $gaji->karyawan->nik ?? '-' }}</dd>
                <dt>Posisi:</dt>
                <dd>{{ $gaji->karyawan->posisi }}</dd>
                <dt>Tanggal Pembayaran:</dt>
                <dd>{{ $gaji->tanggal_pembayaran ? $gaji->tanggal_pembayaran->translatedFormat('d F Y') : 'Belum Ditentukan' }}</dd>
            </dl>
        </div>

        <div class="salary-details">
            <h2>Rincian Penghasilan</h2>
            <dl class="detail-grid">
                <dt>Gaji Pokok:</dt>
                <dd>Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</dd>
                {{-- Tambahkan tunjangan lain jika ada --}}
                {{-- <dt>Tunjangan Transport:</dt>
                <dd>Rp {{ number_format($gaji->tunjangan_transport ?? 0, 0, ',', '.') }}</dd>
                <dt>Tunjangan Makan:</dt>
                <dd>Rp {{ number_format($gaji->tunjangan_makan ?? 0, 0, ',', '.') }}</dd> --}}
            </dl>
        </div>

        <div class="salary-details">
            <h2>Rincian Potongan</h2>
            <dl class="detail-grid">
                <dt>Potongan Absensi:</dt>
                <dd>Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</dd>
                <dd class="col-span-2 text-xs text-gray-500 pl-2">
                    (Hadir: {{ $gaji->total_hadir }}, Izin: {{ $gaji->total_izin }}, Sakit: {{ $gaji->total_sakit }}, Alpha: {{ $gaji->total_tanpa_keterangan }})
                </dd>
                {{-- Tambahkan potongan lain jika ada (BPJS, PPh 21, dll) --}}
                {{-- <dt>Potongan BPJS Kesehatan:</dt>
                <dd>Rp {{ number_format($gaji->potongan_bpjs_kes ?? 0, 0, ',', '.') }}</dd>
                <dt>Potongan PPh 21:</dt>
                <dd>Rp {{ number_format($gaji->potongan_pph21 ?? 0, 0, ',', '.') }}</dd> --}}
            </dl>
        </div>

        <div class="salary-summary">
            <dl class="detail-grid">
                <dt>Total Penghasilan:</dt>
                <dd>Rp {{ number_format($gaji->gaji_pokok /* + tunjangan lain */, 0, ',', '.') }}</dd>
                <dt>Total Potongan:</dt>
                <dd>Rp {{ number_format($gaji->potongan /* + potongan lain */, 0, ',', '.') }}</dd>
                <dt style="font-size: 16px; color: #222;">Gaji Bersih (Take Home Pay):</dt>
                <dd style="font-size: 16px; color: #222;">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</dd>
            </dl>
        </div>

         @if($gaji->keterangan_gaji)
        <div class="mt-4 p-3 bg-gray-50 rounded-md text-sm text-gray-700">
            <strong>Keterangan Tambahan:</strong>
            <p class="whitespace-pre-line">{{ $gaji->keterangan_gaji }}</p>
        </div>
        @endif


        <div class="footer no-print">
            <p>Ini adalah slip gaji yang dicetak secara otomatis oleh sistem.</p>
            <p>© {{ date('Y') }} {{ config('app.company_name', 'Nama Perusahaan Anda') }}. Semua hak dilindungi.</p>
        </div>
    </div>
    <button onclick="window.print()" class="print-button no-print">Cetak Slip Gaji</button>
</body>
</html>