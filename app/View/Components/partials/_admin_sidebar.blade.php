<!-- Desktop Sidebar -->
<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-white border-r">
        <div class="flex items-center h-16 px-6 border-b">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">{{ config('app.name', 'Payroll App') }}</a>
        </div>
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex-1 px-4 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-sm font-medium rounded-md group">
                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.karyawan.index') }}" class="{{ request()->routeIs('admin.karyawan.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-sm font-medium rounded-md group">
                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Kelola Karyawan
                </a>
                <a href="{{ route('admin.payroll.rekap_absensi') }}" class="{{ request()->routeIs('admin.payroll.rekap_absensi') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-sm font-medium rounded-md group">
                     <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Rekap Absensi
                </a>
                <a href="{{ route('admin.payroll.daftar_gaji') }}" class="{{ request()->routeIs('admin.payroll.daftar_gaji') || request()->routeIs('admin.payroll.hitung_gaji') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-sm font-medium rounded-md group">
                     <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599.97M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-.97M12 16c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2 1.343-2 3-2m0 8c1.11 0 2.08.402 2.599.97M12 16V15m0 1v-8m0 0V7m0-1c-1.11 0-2.08.402-2.599.97M12 4c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2m0-8c1.11 0 2.08.402 2.599.97M12 4V3m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-.97M12 20c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3-2-1.343-2-3-2m0 8c1.11 0 2.08.402 2.599.97M12 20V19m0 1v-8m0 0V11m0-1c-1.11 0-2.08.402-2.599.97"></path></svg>
                    Penggajian
                </a>
            </nav>

            <!-- Bottom nav items -->
            <div class="px-4 py-4 mt-auto">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 group">
                    <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Akun
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 group">
                        <svg class="w-5 h-5 mr-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log out
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mobile sidebar -->
<div id="mobileSidebar" class="fixed inset-0 z-40 hidden md:hidden">
    <div class="absolute inset-0 bg-gray-600 opacity-75" id="sidebarOverlay"></div>
    <div class="relative flex flex-col w-64 max-w-xs pb-4 bg-white">
        <div class="absolute top-0 right-0 pt-2 pr-2 -mr-12">
            <button id="closeSidebar" class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex items-center h-16 px-6 border-b">
             <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">{{ config('app.name', 'Payroll App') }}</a>
        </div>
        <div class="flex flex-col flex-1 mt-5 overflow-y-auto">
            <nav class="flex-1 px-2 space-y-1">
                <!-- Navigasi sama seperti desktop, bisa di-copy-paste atau di-include sebagai partial terpisah -->
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-base font-medium rounded-md group">
                    Dashboard
                </a>
                <a href="{{ route('admin.karyawan.index') }}" class="{{ request()->routeIs('admin.karyawan.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-base font-medium rounded-md group">
                    Kelola Karyawan
                </a>
                <a href="{{ route('admin.payroll.rekap_absensi') }}" class="{{ request()->routeIs('admin.payroll.rekap_absensi') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-base font-medium rounded-md group">
                    Rekap Absensi
                </a>
                 <a href="{{ route('admin.payroll.daftar_gaji') }}" class="{{ request()->routeIs('admin.payroll.daftar_gaji') || request()->routeIs('admin.payroll.hitung_gaji') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} flex items-center px-2 py-2 text-base font-medium rounded-md group">
                    Penggajian
                </a>
                 <hr class="my-4">
                <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex items-center px-2 py-2 text-base font-medium rounded-md group">
                    Pengaturan Akun
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex items-center px-2 py-2 text-base font-medium rounded-md group">
                        Log out
                    </a>
                </form>
            </nav>
        </div>
    </div>
</div>