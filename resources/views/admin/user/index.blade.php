@extends('layouts.admin')

@section('title', $view === 'shifts' ? 'Manajemen Shift' : 'Manajemen Pegawai')

@section('content')
@if($view === 'shifts')
    <!-- ================= MANAJEMEN SHIFT ================= -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Tabel Shift</h2>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Shift Type Filter -->
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <a href="{{ route('admin.users.index', ['view' => 'shifts']) }}" 
                       class="px-4 py-1.5 rounded-md text-xs font-bold transition {{ $shiftType == 'all' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Semua</a>
                    <a href="{{ route('admin.users.index', ['view' => 'shifts', 'shift' => 'siang']) }}" 
                       class="px-4 py-1.5 rounded-md text-xs font-bold transition {{ $shiftType == 'siang' ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Pagi</a>
                    <a href="{{ route('admin.users.index', ['view' => 'shifts', 'shift' => 'malam']) }}" 
                       class="px-4 py-1.5 rounded-md text-xs font-bold transition {{ $shiftType == 'malam' ? 'bg-yellow-400 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Siang</a>
                </div>

                <button onclick="openShiftModal('create')" class="bg-[#b91c1c] hover:bg-red-800 text-white font-semibold py-2.5 px-6 rounded-lg flex items-center gap-2 transition-all shadow-md">
                    <i class="fas fa-plus text-sm"></i>
                    Tambah Shift
                </button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-100">
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">No</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">Pegawai</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">Tanggal</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">Mulai</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">Selesai</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase">Kategori</th>
                        <th class="py-4 px-6 font-semibold text-gray-400 text-sm uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($shifts as $index => $shift)
                    @php
                        $hour = \Carbon\Carbon::parse($shift->start_time)->hour;
                        $isPagi = $hour < 14;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-gray-600 font-medium">{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($shift->user)
                                    <div class="relative">
                                        @if($shift->user->photo)
                                            <img src="{{ asset('storage/' . $shift->user->photo) }}" class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200">
                                                {{ substr($shift->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $shift->user->name }}</div>
                                        @if($shift->user->trashed())
                                            <span class="text-[9px] text-red-500 font-bold uppercase tracking-tighter italic">Terhapus</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-sm">User Terhapus</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($shift->start_time)->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-gray-800 font-bold">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                        <td class="py-4 px-6 text-gray-600">
                            @if($shift->end_time)
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                            @else
                                <span class="text-gray-400 opacity-60">{{ $isPagi ? '20:00' : '00:00' }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($isPagi)
                                <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">Pagi</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-[10px] font-bold uppercase tracking-wider">Siang</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <button onclick="openShiftModal('edit', {{ $shift->id }}, '{{ $shift->user_id }}', '{{ \Carbon\Carbon::parse($shift->start_time)->format('Y-m-d') }}', '{{ $isPagi ? 'siang' : 'malam' }}')" 
                                        class="flex items-center gap-2 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-lg transition-all text-gray-700 font-bold text-xs bg-white shadow-sm">
                                    <i class="far fa-edit"></i> Edit
                                </button>
                                <form id="delete-shift-{{ $shift->id }}" action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openModal('modelConfirm', 'Apakah Anda yakin ingin menghapus shift ini?', () => document.getElementById('delete-shift-{{ $shift->id }}').submit())"
                                            class="flex items-center gap-2 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-lg transition-all text-gray-700 font-bold text-xs bg-white shadow-sm text-red-600">
                                        <i class="fas fa-times"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $role == 'kasir' ? 'Daftar Kasir' : 'Daftar Pegawai' }}</h2>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Role/Jabatan Filter -->
                @if($role != 'kasir')
                <div class="relative min-w-[160px]">
                    <select id="userRoleFilter" onchange="applyUserFilters()" 
                            class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm shadow-sm">
                        <option value="all">Semua Jabatan</option>
                        @foreach($allJabatans as $jb)
                            <option value="{{ $jb }}">{{ $jb }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
                @else
                    <input type="hidden" id="userRoleFilter" value="all">
                @endif

                <!-- Search bar -->
                <div class="relative w-full md:w-64">
                    <input type="text" id="userSearchInput" onkeyup="applyUserFilters()" placeholder="Search..." 
                           class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Tambah Button -->
                <button onclick="toggleModal('{{ $role == 'kasir' ? 'modalTambahKasir' : 'modalTambahUser' }}')" class="bg-[#b91c1c] hover:bg-red-800 text-white font-black py-2.5 px-6 rounded-xl flex items-center gap-2 transition-all shadow-lg text-sm active:scale-95 shadow-red-500/20">
                    <i class="fas fa-plus text-[10px]"></i>
                    {{ $role == 'kasir' ? 'Tambah Kasir' : 'Tambah Pegawai' }}
                </button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl">
            <table class="w-full text-left border-collapse" id="userTable">
                <thead>
                    <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[11px]">
                        <th class="py-5 px-6 font-bold text-gray-400">Profil</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Nama Lengkap</th>
                        @if($role == 'kasir')
                        <th class="py-5 px-6 font-bold text-gray-400">Username</th>
                        <th class="py-5 px-6 font-bold text-gray-400">No Hp</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Terdaftar</th>
                        @else
                        <th class="py-5 px-6 font-bold text-gray-400">Jabatan</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Kontak</th>
                        @endif
                        <th class="py-5 px-6 font-bold text-gray-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors user-row group" 
                        data-name="{{ strtolower($user->name) }}" 
                        data-jabatan="{{ ucfirst(strtolower($user->jabatan ?? ($user->role == 'kasir' ? 'Kasir' : 'Admin'))) }}"
                        data-username="{{ strtolower($user->username) }}">
                        <td class="py-5 px-6">
                            <div class="relative w-14 h-14">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" class="h-14 w-14 rounded-2xl object-cover shadow-sm border border-gray-100 group-hover:scale-105 transition-transform">
                                @else
                                    <div class="h-14 w-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 font-black text-xl border border-gray-100">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="absolute -bottom-1 -right-1 block h-4 w-4 rounded-full border-2 border-white {{ $user->isOnline() ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-gray-300' }}"></span>
                            </div>
                        </td>
                        <td class="py-5 px-6 font-black text-gray-800 text-base">
                            {{ $user->name }}
                        </td>
                        @if($role == 'kasir')
                        <td class="py-5 px-6 text-gray-500 font-bold">
                            {{ $user->username }}
                        </td>
                        <td class="py-5 px-6 text-gray-400 font-medium">
                            {{ $user->phone_number ?? '-' }}
                        </td>
                        <td class="py-5 px-6 text-gray-400 text-xs font-bold uppercase">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        @else
                        <td class="py-5 px-6">
                            @php
                                $jabRaw = $user->jabatan ?? ($user->role == 'kasir' ? 'Kasir' : 'Admin');
                                $jabatan = ucfirst(strtolower(trim($jabRaw)));
                                $badgeClass = match($jabatan) {
                                    'Admin' => 'bg-red-50 text-red-600 border-red-100',
                                    'Kasir' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Barista' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Waiter' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    'Cleaner' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    default => 'bg-gray-50 text-gray-600 border-gray-100'
                                };
                            @endphp
                            <span class="{{ $badgeClass }} border py-1.5 px-3 rounded-xl text-[10px] font-black uppercase tracking-wide">
                                {{ $jabatan }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-gray-400 font-bold text-sm">
                            {{ $user->phone_number ?? '-' }}
                        </td>
                        @endif
                        <td class="py-5 px-6">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick="openEditUserModal({{ json_encode($user) }})" 
                                   class="flex items-center gap-2 border border-gray-200 hover:border-gray-800 px-5 py-2 rounded-xl transition-all text-gray-700 font-black text-xs bg-white shadow-sm hover:scale-105 active:scale-95">
                                    Edit
                                </button>
                                <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openModal('modelConfirm', 'Apakah Anda yakin ingin menghapus {{ $user->role == 'kasir' ? 'kasir' : 'pegawai' }} {{ $user->name }}?', () => document.getElementById('delete-user-{{ $user->id }}').submit())"
                                            class="flex items-center gap-2 border border-gray-200 hover:border-red-600 hover:text-red-600 px-5 py-2 rounded-xl transition-all text-gray-700 font-black text-xs bg-white shadow-sm hover:scale-105 active:scale-95">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- ================= MODALS ================= -->

<!-- Modal Tambah User -->
<div id="modalTambahUser" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalTambahUser')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalTambahUserContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="relative flex items-center justify-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Tambah pegawai</h3>
                    <button onclick="toggleModal('modalTambahUser')" class="absolute right-0 top-0 text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm border-gray-300">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" id="inputTambahJabatan" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm border-gray-300">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">No Hp</label>
                        <input type="text" name="phone_number" class="w-full border border-blue-400 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm ring-2 ring-blue-50/50">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil</label>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer bg-[#c5e6fb] hover:bg-blue-200 text-[#0066cc] px-6 py-2.5 rounded-full font-bold text-xs transition-all shadow-sm border border-blue-100">
                                Choose File
                                <input type="file" name="photo" class="hidden" onchange="updateFileName(this, 'fileNameTambahUser')">
                            </label>
                            <span id="fileNameTambahUser" class="text-xs text-gray-400 font-medium">No file chosen</span>
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-lg">Tambah Pegawai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kasir (Baru) -->
<div id="modalTambahKasir" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalTambahKasir')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalTambahKasirContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="relative flex items-center justify-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Kasir Baru</h3>
                    <button onclick="toggleModal('modalTambahKasir')" class="absolute right-0 top-0 text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.assignKasir') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Pegawai</label>
                        <div class="relative">
                            <select name="user_id" required class="w-full appearance-none border border-gray-300 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                                <option value="" disabled selected>-- Pilih Pegawai --</option>
                                @foreach($potentialKasirs as $pk)
                                    <option value="{{ $pk->id }}">{{ $pk->name }} ({{ $pk->jabatan }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-sm"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" required class="w-full border border-blue-100 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-blue-50/30 shadow-sm border-gray-300">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required class="w-full border border-blue-100 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-blue-50/30 shadow-sm border-gray-300">
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-lg">Daftarkan Kasir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalEditUser')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalEditUserContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="relative flex items-center justify-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Edit Pegawai</h3>
                    <button onclick="toggleModal('modalEditUser')" class="absolute right-0 top-0 text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form id="editUserForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="editUserName" required class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" id="editUserJabatan" required class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">No Hp</label>
                        <input type="text" name="phone_number" id="editUserPhone" class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    @if($role == 'kasir')
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" id="editUserUsername" class="w-full border border-blue-100 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-blue-50/30 shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password (Kosongkan jika tidak ganti)</label>
                        <input type="password" name="password" class="w-full border border-blue-100 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-blue-50/30 shadow-sm">
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil</label>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer bg-[#c5e6fb] hover:bg-blue-200 text-[#0066cc] px-6 py-2.5 rounded-full font-bold text-xs transition-all shadow-sm border border-blue-100">
                                Choose File
                                <input type="file" name="photo" class="hidden" onchange="updateFileName(this, 'fileNameEditUser')">
                            </label>
                            <span id="fileNameEditUser" class="text-xs text-gray-400 font-medium">No file chosen</span>
                        </div>
                    </div>
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-4 rounded-xl transition-all shadow-lg text-lg">Simpan Pegawai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Shift Redux -->
<div id="modalShift" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalShift')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalShiftContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800" id="shiftModalTitle">Tambah Shift</h3>
                    <button onclick="toggleModal('modalShift')" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form id="shiftForm" action="{{ route('admin.shifts.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div id="shiftMethodField"></div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pegawai</label>
                        <div class="relative">
                            <select name="user_id" id="inputShiftUser" required class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                                @foreach($kasirs as $kasir)
                                    <option value="{{ $kasir->id }}">{{ $kasir->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-sm"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="date" id="inputShiftDate" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Shift</label>
                        <div class="relative">
                            <select name="category" id="inputShiftCategory" required class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                                <option value="siang">Pagi (10:00 - 20:00)</option>
                                <option value="malam">Siang (14:00 - 00:00)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-sm"></i></div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b22a25] text-white font-bold py-4 rounded-xl hover:bg-red-900 transition-all shadow-lg text-lg">Simpan Shift</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(modalId + 'Content');
        
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                if (content) {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        } else {
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function updateFileName(input, targetId) {
        const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
        document.getElementById(targetId).innerText = fileName;
    }

    function applyUserFilters() {
        const keyword = document.getElementById('userSearchInput').value.toLowerCase();
        const role = document.getElementById('userRoleFilter').value;
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const name = row.dataset.name;
            const jabatan = row.dataset.jabatan;
            const username = row.dataset.username;
            
            const matchKeyword = name.includes(keyword) || username.includes(keyword);
            const matchRole = role === 'all' || jabatan === role;

            row.style.display = (matchKeyword && matchRole) ? '' : 'none';
        });
    }

    function openEditUserModal(user) {
        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${user.id}`;
        
        document.getElementById('editUserName').value = user.name;
        document.getElementById('editUserJabatan').value = user.jabatan || (user.role == 'kasir' ? 'Kasir' : 'Admin');
        document.getElementById('editUserPhone').value = user.phone_number || '';
        
        const usernameField = document.getElementById('editUserUsername');
        if (usernameField) {
            usernameField.value = user.username || '';
        }

        document.getElementById('fileNameEditUser').innerText = 'No file chosen';
        
        toggleModal('modalEditUser');
    }

    function openShiftModal(mode, id = null, userId = null, date = null, category = 'siang') {
        const form = document.getElementById('shiftForm');
        const title = document.getElementById('shiftModalTitle');
        const methodField = document.getElementById('shiftMethodField');

        if (mode === 'create') {
            title.innerText = 'Tambah Shift';
            form.action = "{{ route('admin.shifts.store') }}";
            methodField.innerHTML = '';
            document.getElementById('inputShiftDate').valueAsDate = new Date();
        } else {
            title.innerText = 'Edit Shift';
            form.action = "/admin/shifts/" + id;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('inputShiftUser').value = userId;
            document.getElementById('inputShiftDate').value = date;
            document.getElementById('inputShiftCategory').value = category;
        }
        toggleModal('modalShift');
    }
</script>

<style>
    /* Custom scrollbar for better look */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
</style>
@endsection
