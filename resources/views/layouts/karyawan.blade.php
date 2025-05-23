{{-- resources/views/layouts/karyawan.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Payroll App') }} - @yield('title', 'Karyawan Area')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Karyawan -->
        @include('partials._karyawan_sidebar') {{-- INI YANG BERBEDA --}}

        <!-- Mobile sidebar toggle -->
        <div class="md:hidden fixed bottom-4 right-4 z-50">
            <button id="sidebarToggle" class="p-3 bg-white rounded-full shadow-lg">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            @include('partials._header') {{-- Header bisa sama dengan admin --}}

            <main class="relative flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                        @if (session('success'))
                            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                         @if (session('warning'))
                            <div class="mb-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle (pastikan ID di _karyawan_sidebar.blade.php sesuai)
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileSidebar = document.getElementById('mobileSidebarKaryawan'); // Ganti ID jika perlu, sesuaikan dengan _karyawan_sidebar
        const closeSidebar = document.getElementById('closeSidebarKaryawan'); // Ganti ID jika perlu
        const sidebarOverlay = document.getElementById('sidebarOverlayKaryawan'); // Ganti ID jika perlu

        if (sidebarToggle && mobileSidebar && closeSidebar && sidebarOverlay) {
            sidebarToggle.addEventListener('click', () => {
                mobileSidebar.classList.remove('hidden');
            });
            closeSidebar.addEventListener('click', () => {
                mobileSidebar.classList.add('hidden');
            });
            sidebarOverlay.addEventListener('click', () => {
                mobileSidebar.classList.add('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>