<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Kopi Pemuda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: 'Open Sans', sans-serif; scrollbar-gutter: stable; min-height: 100%; }
        .active-nav { background-color: #1f2937; color: white; }
        [x-cloak] { display: none !important; }
        
        /* Bulletproof fix for SweetAlert2 breaking layout height */
        html.swal2-shown, body.swal2-shown, 
        html.swal2-height-auto, body.swal2-height-auto {
            height: 100% !important;
            min-height: 100% !important;
            overflow: hidden !important;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex min-h-screen max-h-screen overflow-hidden" x-data="{ mobileMenuOpen: false }">

    <!-- Sidebar -->
    <div :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
         class="fixed inset-y-0 left-0 w-64 bg-[#1c1c1c] text-white flex flex-col font-sans transition-transform duration-300 ease-in-out z-30">
        <div class="h-16 flex items-center px-6 bg-black z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 object-contain invert">
                <span class="font-bold text-xl tracking-wider text-white">KOPI PEMUDA</span>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-6 py-4 transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-l-4 border-red-600 text-red-500' : 'text-gray-400 hover:bg-gray-700/30' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('admin.laporan.index') }}" 
               class="flex items-center px-6 py-4 transition-colors {{ request()->routeIs('admin.laporan.*') ? 'border-l-4 border-red-600 text-red-500' : 'text-gray-400 hover:bg-gray-700/30' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium">Laporan</span>
            </a>

            <a href="{{ route('admin.menus.index') }}" 
               class="flex items-center px-6 py-4 transition-colors {{ request()->routeIs('admin.menus.*') ? 'border-l-4 border-red-600 text-red-500 font-bold' : 'text-gray-400 hover:bg-gray-700/30' }}">
                <img src="{{ asset('images/product-icon.png') }}" class="w-5 h-5 mr-3 object-contain brightness-0 invert {{ request()->routeIs('admin.menus.*') ? 'sepia saturate-[5] hue-rotate-[-50deg]' : 'opacity-60' }}">
                <span class="font-medium">Manajemen Produk</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.shifts.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                   class="w-full flex items-center justify-between px-6 py-4 transition-colors {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.shifts.*') ? 'border-l-4 border-red-600 text-red-500 font-bold' : 'text-gray-400 hover:bg-gray-700/30' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-medium">Manajemen Pegawai</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                
                <div x-show="open" x-transition class="bg-black/20 py-2">
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center pl-14 py-2.5 text-sm transition-colors {{ request()->routeIs('admin.users.index') && !request('role') && !request('view') ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Daftar pegawai
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'kasir']) }}" 
                       class="flex items-center pl-14 py-2.5 text-sm transition-colors {{ request('role') == 'kasir' ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Daftar kasir
                    </a>
                    <a href="{{ route('admin.users.index', ['view' => 'shifts']) }}" 
                       class="flex items-center pl-14 py-2.5 text-sm transition-colors {{ request('view') == 'shifts' ? 'text-red-500 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Daftar shift
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center px-6 py-4 transition-colors {{ request()->routeIs('admin.settings.*') ? 'border-l-4 border-red-600 text-red-500' : 'text-gray-400 hover:bg-gray-700/30' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium">Pengaturan</span>
            </a>
        </nav>
        
        <div class="mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center px-6 py-4 bg-[#b91c1c] text-white hover:bg-red-800 transition-colors font-bold uppercase text-xs tracking-wider">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition opacity-ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition opacity-ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden">
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden bg-gray-50 lg:ml-64">
        <!-- Header -->
        <header class="bg-black shadow-md h-16 flex justify-between items-center px-4 lg:px-6 z-20">
            <div class="flex items-center text-white">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-md hover:bg-gray-800 focus:outline-none mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="lg:hidden flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" class="w-6 h-6 object-contain invert">
                    <span class="font-bold text-sm tracking-wider">KOPI PEMUDA</span>
                </div>
            </div>
            
            <div class="flex items-center gap-4 text-white">
                <div class="relative" id="notificationDropdown">
                    <button class="relative focus:outline-none p-2 rounded-full hover:bg-gray-800 transition" onclick="toggleNotifications()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-red-600 ring-2 ring-black"></span>
                        @endif
                    </button>
                    
                    <!-- Notification Menu -->
                    <div id="notifMenu" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50 hidden border border-gray-200 text-gray-800">
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <span class="font-bold text-sm text-gray-700">Notifikasi</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition {{ $notification->unread() ? 'bg-blue-50/30' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user-clock text-blue-600 text-xs text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[13px] leading-snug">
                                                <span class="font-bold text-gray-800">{{ $notification->data['user_name'] }}</span> 
                                                <span class="text-gray-600 text-[13px]">{{ $notification->data['message'] }}</span>
                                            </p>
                                            <span class="text-[10px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-gray-400">
                                    <i class="fas fa-bell-slash text-2xl mb-2 block opacity-20"></i>
                                    <span class="text-xs">Tidak ada notifikasi baru</span>
                                </div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 text-center border-t border-gray-50 flex justify-between gap-2">
                            <button onclick="markAsRead()" class="text-[10px] text-blue-600 font-bold hover:underline">Tandai Dibaca</button>
                            <button onclick="clearNotifications()" class="text-[10px] text-red-600 font-bold hover:underline">Hapus Semua</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <script>
            function toggleNotifications() {
                const menu = document.getElementById('notifMenu');
                menu.classList.toggle('hidden');
            }

            // Close when clicking outside
            window.addEventListener('click', function(e) {
                if (!document.getElementById('notificationDropdown').contains(e.target)) {
                    document.getElementById('notifMenu').classList.add('hidden');
                }
            });

            function markAsRead() {
                fetch('/admin/notifications/mark-read').then(() => {
                    window.location.reload();
                });
            }

            function clearNotifications() {
                if(confirm('Hapus semua riwayat notifikasi?')) {
                    fetch('/admin/notifications/clear-all').then(() => {
                        window.location.reload();
                    });
                }
            }
        </script>

        <!-- Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="modelConfirm" class="fixed hidden z-[300] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop with Blur - Softer and more transparent -->
        <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-500"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Box - Premium rounded-3xl and shadow -->
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all sm:my-8 sm:w-full sm:max-w-md animate-modal-pop border border-gray-100">
                <div class="bg-white px-8 pb-8 pt-10 text-center">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-red-50 mb-6 group">
                        <svg class="h-12 w-12 text-red-600 animate-pulse-gentle group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black leading-tight text-gray-900 mb-2" id="modal-title">Konfirmasi</h3>
                    <p id="modalConfirmMessage" class="text-base text-gray-500 font-medium px-4">Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="px-8 pb-10 flex flex-col gap-3">
                    <button id="confirmBtn" type="button" class="w-full justify-center rounded-2xl bg-[#b91c1c] px-6 py-4 text-sm font-black text-white shadow-xl shadow-red-500/20 hover:bg-red-800 transition-all active:scale-[0.98]">
                        Ya, Saya Yakin
                    </button>
                    <button onclick="closeModal('modelConfirm')" type="button" class="w-full justify-center rounded-2xl bg-gray-50 px-6 py-4 text-sm font-bold text-gray-600 hover:bg-gray-100 transition-all active:scale-[0.98]">
                        Tidak, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes modal-pop {
            0% { opacity: 0; transform: scale(0.95) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal-pop {
            animation: modal-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes pulse-gentle {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        .animate-pulse-gentle {
            animation: pulse-gentle 2s ease-in-out infinite;
        }
    </style>

    <script type="text/javascript">
        window.openModal = function(modalId, message = null, onConfirm = null) {
            if (message) {
                document.getElementById('modalConfirmMessage').innerText = message;
            }
            if (onConfirm) {
                document.getElementById('confirmBtn').onclick = onConfirm;
            }
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden');
        }

        window.closeModal = function(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden');
        }

        // Close all modals when press ESC
        document.onkeydown = function(event) {
            event = event || window.event;
            if (event.keyCode === 27) {
                document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden');
                document.querySelectorAll('[id^="modal"], #modelConfirm').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        };

        // SweetAlert Success/Error Animations
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#b91c1c',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#b91c1c'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                title: 'Kesalahan!',
                text: "{{ $errors->first() }}",
                icon: 'warning',
                confirmButtonColor: '#b91c1c'
            });
        @endif
    </script>
</body>
</html>
