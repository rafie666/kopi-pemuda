<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') - Kopi Pemuda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col overflow-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Mobile Menu Overlay -->
    <div x-cloak x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-black/50 z-[60] lg:hidden"></div>

    <!-- Mobile Slide-out Menu -->
    <div x-cloak x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed right-0 top-0 h-full w-64 bg-white z-[70] shadow-2xl lg:hidden flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-black text-white">
            <h2 class="font-bold">Menu</h2>
            <button @click="mobileMenuOpen = false" class="text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 flex-1 bg-white">
            <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-100">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Kasir Aktif</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <a href="{{ route('kasir.shift') }}" class="w-full bg-red-50 text-red-700 font-bold py-3 px-4 rounded-xl flex items-center gap-3 mb-3 transition border border-red-100 hover:bg-red-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Manajemen Shift
            </a>

            <a href="{{ route('kasir.transaksi.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-xl flex items-center gap-3 mb-4 transition border border-gray-200">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Riwayat Transaksi
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl flex justify-center items-center gap-2 shadow-lg shadow-red-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Header (Black) -->
    <header class="bg-black text-white p-3 lg:p-4 shadow-md z-20">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Logo & User -->
            <div class="w-full md:w-auto flex justify-between items-center gap-3">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 object-contain invert">
                    <div class="text-lg lg:text-xl font-bold tracking-wider">KOPI PEMUDA</div>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Mobile User Info -->
                    <div class="md:hidden flex items-center gap-1 text-[12px]">
                        <span class="text-gray-400">Halo,</span>
                        <span class="text-red-500 font-bold truncate max-w-[80px]">{{ Auth::user()->name }}</span>
                    </div>

                    <!-- Hamburger Button -->
                    <button @click="mobileMenuOpen = true" class="md:hidden p-1.5 bg-gray-800 rounded-lg text-white hover:bg-gray-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="w-full md:flex-1 md:max-w-xl">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" id="searchInput" placeholder="Cari Produk" class="w-full bg-gray-200 text-black rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-gray-400">
                </div>
            </div>

            <!-- User & Logout (Desktop) -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('kasir.shift') }}" class="text-sm font-bold text-red-50 hover:text-white transition flex items-center gap-2 bg-red-900 border border-red-700 hover:bg-red-800 px-4 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Manajemen Shift
                </a>
                <a href="{{ route('kasir.transaksi.index') }}" class="text-sm font-bold text-gray-300 hover:text-white transition flex items-center gap-2 bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Riwayat Transaksi
                </a>
                <div class="flex items-center gap-1 text-lg">
                    <span class="text-gray-400">Hallo,</span>
                    <span class="text-red-500 font-bold">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 flex overflow-hidden relative">
        @yield('content')
    </main>

</body>
</html>
