<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token tidak terlalu dibutuhkan untuk halaman welcome statis, tapi tidak masalah jika ada --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>{{ config('app.name', 'Laravel Payroll Service') }} - Selamat Datang</title>

    <!-- Fonts (Contoh menggunakan Bunny Fonts) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|montserrat:600,700" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi minimal Tailwind jika diperlukan (misalnya, untuk font atau warna kustom)
        // Jika tidak ada kustomisasi tema yang signifikan, bagian theme bisa dikosongkan atau dihapus.
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'], // Menggunakan font Inter sebagai default
                        display: ['Montserrat', 'sans-serif'], // Untuk judul besar jika perlu
                    },
                    colors: { // Contoh jika ingin menambahkan warna kustom
                        'brand-purple': '#4A00E0',
                        'brand-pink': '#8E2DE2',
                        'primary': { // Contoh warna primary seperti indigo
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1', // Mirip indigo-500
                            600: '#4f46e5', // Mirip indigo-600
                            700: '#4338ca', // Mirip indigo-700
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
            // Jika Anda tidak menggunakan fitur seperti @apply di CSS eksternal atau plugin,
            // konfigurasi di atas sudah cukup.
        }
    </script>
    <style>
        /* Menerapkan font dasar ke body */
        body {
            font-family: 'Inter', sans-serif; /* Sama dengan tailwind.config.theme.extend.fontFamily.sans */
        }
        /*
           Jika menggunakan CDN, kelas dengan @apply TIDAK akan bekerja di file CSS terpisah.
           Anda harus menulis kelas Tailwind langsung di HTML atau mendefinisikan gaya kustom
           dengan CSS biasa di sini jika ada yang tidak bisa dicapai dengan kelas utilitas Tailwind.
           Untuk tombol, kita akan menggunakan kelas utilitas Tailwind langsung di HTML.
        */
    </style>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-primary-500 selection:text-white">
        {{-- Tombol Login/Register di Pojok Kanan Atas --}}
        <div class="absolute top-0 right-0 p-6 z-10">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary-500">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary-500">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-primary-500">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        {{-- Konten Utama Halaman Welcome --}}
        <div class="max-w-3xl mx-auto p-6 lg:p-8 text-center">
            <div class="flex justify-center mb-8">
                {{-- GANTI DENGAN LOGO ANDA --}}
                <svg class="h-20 w-auto text-primary-600 dark:text-primary-400" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011-1h14a1 1 0 011 1v4.586A2.5 2.5 0 0017.5 6h-2.086a2.5 2.5 0 00-4.414 0H9.5a2.5 2.5 0 00-2.086.914L4 10.5V2zm16 6.586L17.414 12A2.5 2.5 0 0017.5 14h.086a2.5 2.5 0 004.414 0H20v7.5a1 1 0 01-1 1H5a1 1 0 01-1-1V12h2.086a2.5 2.5 0 004.414 0H13.5a2.5 2.5 0 002.086-.914L19 7.5V8.586zM6.5 11a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm11 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM6 15h12v2H6v-2z" clip-rule="evenodd" />
                </svg>
            </div>

            <h1 class="mt-2 text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl" style="font-family: 'Montserrat', sans-serif;">
                Selamat Datang di {{ config('app.name', 'Payroll Service') }}
            </h1>

            <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                Solusi efisien untuk manajemen absensi dan penggajian karyawan Anda. Akses mudah, data akurat, proses cepat.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-x-6 gap-y-4">
                @if (Route::has('login'))
                    {{-- Tombol Login dengan kelas utilitas Tailwind --}}
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Masuk ke Akun Anda
                    </a>
                @endif
                @if (Route::has('register'))
                    {{-- Tombol Register dengan kelas utilitas Tailwind --}}
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-8 py-3 bg-white text-primary-600 font-semibold rounded-lg shadow-md hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Daftar Akun Baru <span aria-hidden="true">→</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <footer class="py-12 text-center text-sm text-gray-500 dark:text-gray-400">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
             <p class="mt-2">© {{ date('Y') }} {{ config('app.company_name', config('app.name', 'Perusahaan Anda')) }}. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>