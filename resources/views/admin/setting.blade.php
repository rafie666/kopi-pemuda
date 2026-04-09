@extends('layouts.admin')

@section('title', 'Pengaturan Admin')

@section('content')
<div class="mb-10">
    <h2 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
        <div class="h-10 w-10 bg-red-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
            <i class="fas fa-cog text-lg"></i>
        </div>
        Pengaturan Admin
    </h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Left Column -->
    <div class="space-y-8">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100 group">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8">Profil Utama</h3>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-3xl overflow-hidden border-4 border-white shadow-xl group-hover:scale-105 transition-transform">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                                    <i class="fas fa-user text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-green-500 rounded-full border-4 border-white shadow-sm"></div>
                    </div>
                    <div>
                        <div class="font-black text-gray-900 text-xl tracking-tight">{{ Auth::user()->name }}</div>
                        <div class="text-gray-400 font-medium text-sm">{{ Auth::user()->email ?? Auth::user()->username }}</div>
                        <div class="mt-2">
                             <span class="bg-red-50 text-red-600 border border-red-100 py-1 px-3 rounded-xl text-[10px] font-black uppercase tracking-wide">Administrator</span>
                        </div>
                    </div>
                </div>
                <button onclick="openProfileModal()" class="px-5 py-2.5 border border-gray-200 rounded-xl text-xs font-black text-gray-700 hover:border-gray-800 transition-all hover:scale-105 active:scale-95 bg-white shadow-sm">
                    Edit Profil
                </button>
            </div>
        </div>

        <!-- Store Settings Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center gap-3">
                <div class="h-8 w-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600">
                    <i class="fas fa-store text-sm"></i>
                </div>
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Informasi Toko</h3>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="grid grid-cols-3 px-8 py-5 items-center">
                    <div class="col-span-1 text-gray-400 font-bold text-xs uppercase">Nama Toko</div>
                    <div class="col-span-2 text-gray-800 font-black">Kopi Pemuda</div>
                </div>
                <div class="grid grid-cols-3 px-8 py-5 items-center">
                    <div class="col-span-1 text-gray-400 font-bold text-xs uppercase">Operasional</div>
                    <div class="col-span-2 flex items-center gap-3">
                        <div class="relative">
                            <select class="appearance-none border border-gray-200 rounded-xl px-4 py-2 bg-gray-50 text-gray-700 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition-all cursor-pointer">
                                <option>10:00 AM</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-[10px] text-gray-400"></i>
                        </div>
                        <span class="text-gray-300 font-bold">sampai</span>
                        <div class="relative">
                            <select class="appearance-none border border-gray-200 rounded-xl px-4 py-2 bg-gray-50 text-gray-700 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition-all cursor-pointer">
                                <option>12:00 PM</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-[10px] text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-8">
        <!-- Security Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                    <i class="fas fa-shield-alt text-sm"></i>
                </div>
                <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Keamanan Akun</h3>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl font-bold text-sm flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl font-bold text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.password') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Password Baru</label>
                        <input type="password" name="new_password" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all" placeholder="••••••••">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-black py-4 rounded-xl transition-all shadow-xl shadow-red-500/20 active:scale-[0.98]">
                            Perbarui Password
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Visual Placeholder -->
        <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 h-48 flex items-center justify-center p-8 text-center grayscale opacity-30">
             <i class="fas fa-terminal text-4xl text-gray-300"></i>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div id="profileModal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="closeProfileModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="profileModalContent" class="relative w-full max-w-md transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0 border border-gray-100">
            <div class="px-10 py-10">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Perbarui Profil</h3>
                    <button onclick="closeProfileModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.settings.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-3.5 text-gray-800 font-bold focus:ring-2 focus:ring-red-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Foto Profil</label>
                            <div class="flex flex-col gap-4">
                                <label class="cursor-pointer bg-red-50 hover:bg-red-100 text-red-600 px-6 py-4 rounded-2xl font-black text-sm transition-all border border-red-100 inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Foto Baru
                                    <input type="file" name="photo" accept="image/*" class="hidden" onchange="updateFileName(this, 'fileNameProfile')">
                                </label>
                                <span id="fileNameProfile" class="text-xs text-center text-gray-400 font-medium italic">Belum ada file yang dipilih...</span>
                                
                                @if(Auth::user()->photo)
                                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        <input type="checkbox" name="remove_photo" id="remove_photo" value="1" class="rounded-md border-gray-300 text-red-600 focus:ring-red-500 h-5 w-5">
                                        <label for="remove_photo" class="text-sm text-gray-600 font-bold">Hapus foto saat ini</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="pt-4 flex flex-col gap-3">
                            <button type="submit" 
                                class="w-full py-4 bg-[#b91c1c] hover:bg-red-800 text-white rounded-2xl font-black shadow-xl shadow-red-500/20 transition-all active:scale-[0.98]">
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="closeProfileModal()" 
                                class="w-full py-4 bg-gray-50 text-gray-500 rounded-2xl font-bold hover:bg-gray-100 transition-all">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input, targetId) {
        const fileName = input.files[0] ? input.files[0].name : 'Belum ada file yang dipilih...';
        document.getElementById(targetId).innerText = fileName;
    }
</script>

<script>
    function openProfileModal() {
        const modal = document.getElementById('profileModal');
        const content = document.getElementById('profileModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        const content = document.getElementById('profileModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('profileModal');
        if (event.target == modal) {
            closeProfileModal();
        }
    }
</script>
@endsection
