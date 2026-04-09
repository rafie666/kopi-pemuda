@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div x-data="{ 
    modalTambah: false, 
    modalEdit: false,
    modalPengeluaran: false
}">

<!-- LIBRARY -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<!-- ================= TOP CARDS ================= -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-6 mb-8">

<!-- Pendapatan (Omzet) -->
    <div class="bg-red-600 rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Total Omzet</p>
            <h3 class="text-lg md:text-2xl font-bold">Rp {{ number_format($total_omzet,0,',','.') }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-money-bill-wave text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Pengeluaran -->
    <div class="bg-[#ef4444] rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Total Pengeluaran</p>
            <h3 class="text-lg md:text-2xl font-bold">Rp {{ number_format($total_pengeluaran,0,',','.') }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-file-invoice-dollar text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Laba Bersih -->
    <div class="bg-green-600 rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Laba Bersih</p>
            <h3 class="text-lg md:text-2xl font-bold">Rp {{ number_format($laba_bersih,0,',','.') }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-chart-line text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Transaksi -->
    <div class="bg-[#b91c1c] rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Transaksi Hari Ini</p>
            <h3 class="text-lg md:text-2xl font-bold">{{ $transaksi_hari_ini }} Transaksi</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-cart-shopping text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-gray-800 rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div class="flex-1 overflow-hidden">
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Produk Terlaris</p>
            <h3 class="text-lg md:text-xl font-bold truncate">{{ $produk_terlaris ?: '-' }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-star text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Pegawai -->
    <div class="bg-red-700 rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Total Pegawai</p>
            <h3 class="text-lg md:text-2xl font-bold">{{ $total_kasir }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-users text-xl md:text-2xl text-white"></i></div>
    </div>

    <!-- Total Diskon -->
    <div class="bg-orange-500 rounded-xl p-4 md:p-6 flex items-center justify-between shadow-lg text-white">
        <div>
            <p class="text-[10px] md:text-sm font-medium opacity-90 mb-1">Total Diskon</p>
            <h3 class="text-lg md:text-2xl font-bold">Rp {{ number_format($total_diskon,0,',','.') }}</h3>
        </div>
        <div class="bg-white/20 p-2 md:p-3 rounded-lg"><i class="fa-solid fa-tags text-xl md:text-2xl text-white"></i></div>
    </div>

</div>

<!-- ================= GRAFIK ================= -->
<div class="bg-white rounded-lg shadow-sm p-4 md:p-8 mb-8 border border-gray-100">

    <div class="flex justify-between items-start mb-2">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Grafik Penjualan</h3>
            <p id="chartContext" class="text-sm text-gray-400 font-medium mt-1">Pendapatan - {{ $chart_data['weekly']['context'] }}</p>
        </div>

        <select id="periodSelector"
            class="border border-gray-200 rounded-xl text-sm px-4 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all h-[42px]">
            <option value="weekly">Mingguan</option>
            <option value="monthly">Bulanan</option>
            <option value="yearly">Tahunan</option>
        </select>
    </div>

    <!-- Custom Chart Legend -->
    <div class="flex justify-center items-center gap-6 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-10 h-3 bg-blue-500 rounded-sm border border-blue-600"></div>
            <span class="text-xs font-medium text-gray-500">Pendapatan</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-10 h-3 bg-yellow-400 rounded-sm border border-yellow-500"></div>
            <span class="text-xs font-medium text-gray-500">Transaksi</span>
        </div>
    </div>

    <div class="relative h-[300px] md:h-[450px] w-full mb-4">
        <canvas id="salesChart" class="!w-full !h-full"></canvas>
    </div>

    <div class="flex justify-end gap-2 pr-2">
        <button onclick="downloadExcel()"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-2">
            <span class="bg-white text-green-600 px-1 rounded-[4px] text-[8px] font-black">XLS</span> Excel
        </button>
        <button onclick="downloadPDF()"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-2">
            <span class="bg-white text-red-600 px-1 rounded-[4px] text-[8px] font-black">PDF</span> PDF
        </button>
    </div>

</div>

<!-- ================= TABEL PENGELUARAN (Recent) ================= -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">Pengeluaran Terbaru</h3>
        <button @click="modalPengeluaran = true" class="text-xs font-bold text-red-600 hover:text-red-700 underline">Lihat Semua</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[10px]">
                    <th class="py-4 px-6 font-bold text-gray-400">Tanggal</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Deskripsi</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Kategori</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Jumlah</th>
                    <th class="py-4 px-6 font-bold text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengeluarans as $p)
                <tr class="hover:bg-gray-50 transition-colors text-sm">
                    <td class="py-4 px-6 text-gray-500">{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
                    <td class="py-4 px-6 font-medium text-gray-800">{{ $p->description }}</td>
                    <td class="py-4 px-6 text-gray-400 italic">{{ $p->category }}</td>
                    <td class="py-4 px-6 font-bold text-red-600">-Rp {{ number_format($p->amount,0,',','.') }}</td>
                    <td class="py-4 px-6 text-center">
                        <form action="{{ route('admin.pengeluarans.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition" onclick="return confirm('Hapus pengeluaran ini?')">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-400">Belum ada data pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= TABEL PRODUK ================= -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <h3 class="text-xl md:text-2xl font-bold text-gray-800">Tabel Produk</h3>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[180px]">
                <select id="adminCategoryFilter" onchange="applyAdminFilters()" 
                    class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm">
                    <option value="all">Semua Kategori</option>
                    <option value="Makanan Ringan">Makanan Ringan</option>
                    <option value="Makanan Berat">Makanan Berat</option>
                    <option value="Minuman Ringan">Minuman Ringan</option>
                    <option value="Manual Brew">Manual Brew</option>
                    <option value="Dessert">Dessert</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <div class="relative w-full md:w-64">
                <input type="text" id="adminSearchInput" onkeyup="applyAdminFilters()"
                    class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-sm"
                    placeholder="Search">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <button x-on:click="modalPengeluaran = true"
                class="bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                <i class="fas fa-plus-circle text-xs"></i>
                Tambah Pengeluaran
            </button>

            <button x-on:click="modalTambah = true"
                class="bg-[#b91c1c] hover:bg-red-800 text-white font-bold py-2.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                <i class="fas fa-plus-circle text-xs"></i>
                Tambah Produk
            </button>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl">
        <table class="w-full text-left border-collapse" id="adminProductTable">
            <thead>
                <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[11px]">
                    <th class="py-5 px-6 font-bold text-gray-400 text-center w-32">Foto</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Nama Produk</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Kategori</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Harga</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Stok</th>
                    <th class="py-5 px-6 font-bold text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($menus as $menu)
                <tr class="hover:bg-gray-50 transition-colors dashboard-product-row group" 
                    data-name="{{ strtolower($menu->name) }}" 
                    data-category="{{ $menu->category }}">
                    <td class="py-5 px-6 text-center">
                        <div class="flex justify-center">
                            @if($menu->image)
                                <img src="{{ asset('storage/'.$menu->image) }}"
                                    class="h-14 w-14 object-cover rounded-xl shadow-sm border border-gray-100 group-hover:scale-105 transition-transform">
                            @else
                                <div class="h-14 w-14 bg-gray-50 rounded-xl flex items-center justify-center border border-dashed border-gray-200">
                                    <i class="fas fa-image text-gray-300 text-lg"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="py-5 px-6 font-black text-gray-800 text-base">{{ $menu->name }}</td>
                    <td class="py-5 px-6 text-gray-400 font-bold text-sm italic">{{ $menu->category }}</td>
                    <td class="py-5 px-6 font-black text-gray-800 text-lg tracking-tight">
                        Rp {{ number_format($menu->price,0,',','.') }}
                    </td>
                    <td class="py-5 px-6">
                        <div class="flex items-center gap-2">
                            <button onclick="changeStock({{ $menu->id }}, -1)" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-all text-gray-500 font-bold hover:scale-110 active:scale-95 flex-shrink-0">-</button>
                            <input type="number" id="stock-input-{{ $menu->id }}" value="{{ $menu->stock }}" 
                                onchange="setStock({{ $menu->id }}, this.value)"
                                class="w-16 text-center font-black rounded-lg border border-gray-200 py-1 focus:ring-2 focus:ring-red-500 outline-none {{ $menu->stock <= 5 ? 'text-red-600' : 'text-gray-800' }}">
                            <button onclick="changeStock({{ $menu->id }}, 1)" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-all text-gray-500 font-bold hover:scale-110 active:scale-95 flex-shrink-0">+</button>
                        </div>
                    </td>
                    <td class="py-5 px-6">
                        <div class="flex justify-center items-center gap-3">
                            <button @click='openEditModal(@json($menu)); modalEdit = true'
                                class="flex items-center gap-2 border border-gray-200 hover:border-gray-800 px-5 py-2 rounded-xl transition-all text-gray-700 font-bold text-xs bg-white shadow-sm hover:scale-105 active:scale-95">
                                Edit
                            </button>
                            <form id="delete-form-{{ $menu->id }}" method="POST" action="{{ route('admin.menus.destroy',$menu->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openModal('modelConfirm', 'Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?', () => document.getElementById('delete-form-{{ $menu->id }}').submit())"
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

<!-- Modal Tambah Pengeluaran -->
<div x-show="modalPengeluaran" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md" @click="modalPengeluaran = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Catat Pengeluaran</h3>
                    <button @click="modalPengeluaran = false" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.pengeluarans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Deskripsi / Kebutuhan</label>
                        <input type="text" name="description" required placeholder="Misal: Belanja Kopi, Bayar Listrik" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                                <option value="Belanja Bahan">Belanja Bahan</option>
                                <option value="Gaji Pegawai">Gaji Pegawai</option>
                                <option value="Listrik & Air">Listrik & Air</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-[15px] font-semibold text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Jumlah (Rp)</label>
                        <input type="number" name="amount" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-black transition-all shadow-lg text-lg">Simpan Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div x-show="modalTambah" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md" @click="modalTambah = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Produk</h3>
                    <button @click="modalTambah = false" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Nama produk</label>
                        <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category" required class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                                <option value="" disabled selected></option>
                                <option value="Minuman Ringan">Minuman Ringan</option>
                                <option value="Makanan Ringan">Makanan Ringan</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Manual Brew">Manual Brew</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-sm"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Harga Produk</label>
                        <input type="number" name="price" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Stok Produk</label>
                        <input type="number" name="stock" required min="0" value="0" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Foto Produk</label>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer bg-[#c5e6fb] hover:bg-blue-200 text-[#0066cc] px-6 py-2 rounded-full font-bold text-sm transition-all shadow-sm border border-blue-100">
                                Choose File
                                <input type="file" name="image" class="hidden" onchange="updateFileName(this, 'fileNameTambah')">
                            </label>
                            <span id="fileNameTambah" class="text-sm text-gray-400 font-medium">No file chosen</span>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b22a25] text-white font-bold py-4 rounded-xl hover:bg-red-900 transition-all shadow-lg text-lg">Tambah Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div x-show="modalEdit" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md" @click="modalEdit = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Edit Produk</h3>
                    <button @click="modalEdit = false" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Nama produk</label>
                        <input type="text" name="name" id="editName" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category" id="editCategory" required class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                                <option value="Minuman Ringan">Minuman Ringan</option>
                                <option value="Makanan Ringan">Makanan Ringan</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Manual Brew">Manual Brew</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400"><i class="fas fa-chevron-down text-sm"></i></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Harga Produk</label>
                        <input type="number" name="price" id="editPrice" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Stok Produk</label>
                        <input type="number" name="stock" id="editStock" required min="0" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Foto Produk</label>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer bg-[#c5e6fb] hover:bg-blue-200 text-[#0066cc] px-6 py-2 rounded-full font-bold text-sm transition-all shadow-sm border border-blue-100">
                                Choose File
                                <input type="file" name="image" class="hidden" onchange="updateFileName(this, 'fileNameEdit')">
                            </label>
                            <span id="fileNameEdit" class="text-sm text-gray-400 font-medium">No file chosen</span>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b22a25] text-white font-bold py-4 rounded-xl hover:bg-red-900 transition-all shadow-lg text-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allData = @json($chart_data);
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Gradient for area chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allData.weekly.labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: allData.weekly.sales,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Transaksi',
                    data: allData.weekly.trans,
                    type: 'bar',
                    backgroundColor: '#facc15',
                    borderRadius: 4,
                    barThickness: 30,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false // We use custom HTML legend
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1f2937',
                    bodyColor: '#1f2937',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.datasetIndex === 0) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            } else {
                                label += context.parsed.y + ' Transaksi';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11, weight: '500' },
                        color: '#9ca3af'
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: {
                        color: '#f3f4f6'
                    },
                    ticks: {
                        font: { size: 10 },
                        color: '#9ca3af',
                        callback: v => v >= 1000 ? 'Rp ' + (v/1000) + 'k' : 'Rp ' + v
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        font: { size: 10 },
                        color: '#9ca3af',
                        stepSize: 5
                    }
                }
            }
        }
    });

    document.getElementById('periodSelector').addEventListener('change', e => {
        const d = allData[e.target.value];
        salesChart.data.labels = d.labels;
        salesChart.data.datasets[0].data = d.sales;
        salesChart.data.datasets[1].data = d.trans;
        document.getElementById('chartContext').innerText = `Pendapatan - ${d.context}`;
        balanceYAxes(salesChart, d.sales, d.trans);
        salesChart.update();
    });

    // Initial axis balancing
    balanceYAxes(salesChart, allData.weekly.sales, allData.weekly.trans);
    salesChart.update();

    function balanceYAxes(chart, salesData, transData) {
        const maxSales = Math.max(...salesData, 100000);
        const maxTrans = Math.max(...transData, 10);
        // Align ticks roughly
        chart.options.scales.y.max = Math.ceil(maxSales / 100000) * 100000;
        chart.options.scales.y1.max = Math.ceil(maxTrans / 5) * 5;
    }
});

function downloadExcel() {
    const selector = document.getElementById('periodSelector');
    const allData = @json($chart_data);
    const p = selector.value;
    let csv = "Periode,Pendapatan,Transaksi\n";
    allData[p].labels.forEach((l,i)=>{
        csv += `${l},${allData[p].sales[i]},${allData[p].trans[i]}\n`;
    });
    const a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    a.download = `laporan_${p}.csv`;
    a.click();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Laporan Penjualan", 14, 20);
    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas) {
        doc.addImage(chartCanvas.toDataURL(), 'PNG', 14, 30, 180, 90);
    }
    doc.save("laporan_penjualan.pdf");
}

// Session Notifications
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
    });
@endif

function updateFileName(input, targetId) {
    const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
    document.getElementById(targetId).innerText = fileName;
}

function openEditModal(menu) {
    const form = document.getElementById('editForm');
    form.action = `/admin/menus/${menu.id}`;
    document.getElementById('editName').value = menu.name;
    document.getElementById('editCategory').value = menu.category;
    document.getElementById('editPrice').value = menu.price;
    document.getElementById('editStock').value = menu.stock;
    document.getElementById('fileNameEdit').innerText = 'No file chosen';
}

function applyAdminFilters() {
    const keyword = document.getElementById('adminSearchInput').value.toLowerCase();
    const category = document.getElementById('adminCategoryFilter').value;
    const rows = document.querySelectorAll('.dashboard-product-row');
    rows.forEach(row => {
        const name = row.dataset.name;
        const cat = row.dataset.category;
        const matchKeyword = name.includes(keyword);
        const matchCategory = category === 'all' || cat === category;
        row.style.display = (matchKeyword && matchCategory) ? '' : 'none';
    });
}

function setStock(id, val) {
    const stock = parseInt(val);
    if (isNaN(stock) || stock < 0) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Stok tidak valid', timer: 1500, showConfirmButton: false });
        return;
    }

    fetch(`/admin/menus/${id}/quick-stock`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ stock: stock })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStockUI(id, data.new_stock);
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 1500, showConfirmButton: false });
        }
    });
}

function changeStock(id, delta) {
    fetch(`/admin/menus/${id}/quick-stock`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ change: delta })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStockUI(id, data.new_stock);
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 1500, showConfirmButton: false });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem' });
    });
}

function updateStockUI(id, newStock) {
    const input = document.getElementById(`stock-input-${id}`);
    input.value = newStock;
    if (newStock <= 5) {
        input.classList.add('text-red-600');
        input.classList.remove('text-gray-800');
    } else {
        input.classList.remove('text-red-600');
        input.classList.add('text-gray-800');
    }
    
    // Subtle animation feedback
    input.style.transform = 'scale(1.1)';
    setTimeout(() => input.style.transform = 'scale(1)', 100);
}
</script>
</div> <!-- Penutup x-data (Line 7) -->
@endsection
