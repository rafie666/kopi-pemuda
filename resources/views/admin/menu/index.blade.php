@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Daftar Produk</h2>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Category Filter -->
            <div class="relative min-w-[180px]">
                <select id="categoryFilter" onchange="applyFilters()" 
                        class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm shadow-sm">
                    <option value="all">Semua Kategori</option>
                    <option value="Minuman Ringan">Minuman Ringan</option>
                    <option value="Makanan Ringan">Makanan Ringan</option>
                    <option value="Makanan Berat">Makanan Berat</option>
                    <option value="Manual Brew">Manual Brew</option>
                    <option value="Dessert">Dessert</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            <!-- Search bar -->
            <div class="relative w-full md:w-64">
                <input type="text" id="searchInput" onkeyup="applyFilters()" placeholder="Search products..." 
                       class="w-full bg-white border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Add Product Button -->
            <button onclick="toggleModal('modalTambah')" class="bg-[#b91c1c] hover:bg-red-800 text-white font-black py-2.5 px-6 rounded-xl flex items-center gap-2 transition-all shadow-lg text-sm active:scale-95 shadow-red-500/20">
                <i class="fas fa-plus text-[10px]"></i>
                Tambah Produk
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full text-left border-collapse" id="productTable">
            <thead>
                <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[11px]">
                    <th class="py-5 px-6 font-bold text-gray-400">ID</th>
                    <th class="py-5 px-6 font-bold text-gray-400 text-center">Foto</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Nama Produk</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Kategori</th>
                    <th class="py-5 px-6 font-bold text-gray-400">Harga</th>
                    <th class="py-5 px-6 font-bold text-gray-400 text-center">Tersedia</th>
                    <th class="py-5 px-6 font-bold text-gray-400 text-center">Stok</th>
                    <th class="py-5 px-6 font-bold text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($menus as $menu)
                <tr class="hover:bg-gray-50 transition-colors product-row group" 
                    data-name="{{ strtolower($menu->name) }}" 
                    data-category="{{ $menu->category }}">
                    <td class="py-5 px-6 text-gray-400 font-bold text-xs">{{ str_pad($menu->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-5 px-6 text-center">
                        <div class="flex justify-center">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" class="h-16 w-20 object-cover rounded-2xl shadow-sm border border-gray-100 group-hover:scale-105 transition-transform">
                            @else
                                <div class="h-16 w-20 bg-gray-50 rounded-2xl flex items-center justify-center border border-dashed border-gray-200">
                                    <i class="fas fa-image text-gray-200 text-xl"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="py-5 px-6 font-black text-gray-800 text-base">
                        {{ $menu->name }}<br>
                        @if($menu->is_best_seller)
                            <div class="mt-1 text-[10px] bg-red-100 text-red-600 px-2.5 py-0.5 rounded-full inline-flex items-center gap-1"><i class="fas fa-star text-[8px]"></i> Best Seller (Manual)</div>
                        @elseif(in_array($menu->id, $topMenuIds))
                            <div class="mt-1 text-[10px] bg-orange-100 text-orange-600 px-2.5 py-0.5 rounded-full inline-flex items-center gap-1"><i class="fas fa-fire text-[8px]"></i> Best Seller (Auto)</div>
                        @endif
                    </td>
                    <td class="py-5 px-6 font-bold text-gray-500 text-sm italic">{{ $menu->category }}</td>
                    <td class="py-5 px-6 font-black text-gray-800 text-lg tracking-tight">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="py-5 px-6 text-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="toggleStatus({{ $menu->id }}, this.checked)" class="sr-only peer" {{ $menu->is_available ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
                    </td>
                    <td class="py-5 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="changeStock({{ $menu->id }}, -1)" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-all text-gray-500 font-bold hover:scale-110 active:scale-95">-</button>
                            <input type="number" id="stock-input-{{ $menu->id }}" value="{{ $menu->stock }}" 
                                onchange="setStock({{ $menu->id }}, this.value)"
                                class="w-14 text-center font-black rounded-lg border border-gray-200 py-1 focus:ring-2 focus:ring-red-500 outline-none {{ $menu->stock <= 5 ? 'text-red-600' : 'text-gray-800' }}">
                            <button onclick="changeStock({{ $menu->id }}, 1)" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-all text-gray-500 font-bold hover:scale-110 active:scale-95">+</button>
                        </div>
                    </td>
                    <td class="py-5 px-6">
                        <div class="flex justify-center items-center gap-3">
                            <button onclick='openEditModal(@json($menu))' 
                               class="flex items-center gap-2 border border-gray-200 hover:border-gray-800 px-5 py-2 rounded-xl transition-all text-gray-700 font-black text-xs bg-white shadow-sm hover:scale-105 active:scale-95">
                                Edit
                            </button>
                            <form id="delete-menu-{{ $menu->id }}" action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openModal('modelConfirm', 'Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?', () => document.getElementById('delete-menu-{{ $menu->id }}').submit())"
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

<!-- Modal Tambah Produk -->
<div id="modalTambah" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalTambah')"></div>

    <!-- Modal Content Center -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalTambahContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Produk</h3>
                    <button onclick="toggleModal('modalTambah')" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Nama produk</label>
                        <input type="text" name="name" required placeholder=""
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category" required 
                                    class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                                <option value="" disabled selected></option>
                                <option value="Minuman Ringan">Minuman Ringan</option>
                                <option value="Makanan Ringan">Makanan Ringan</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Manual Brew">Manual Brew</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Harga Produk</label>
                        <input type="number" name="price" required placeholder=""
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Stok Produk</label>
                        <input type="number" name="stock" required min="0" value="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
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

                    <div class="form-group flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-[15px] font-bold text-gray-800">Penandaan Best Seller Khusus</label>
                            <p class="text-[11px] text-gray-500 mt-1 max-w-[250px]">Produk ini akan selalu masuk daftar Best Seller secara manual.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="is_best_seller" id="tambah_is_best_seller" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b22a25] text-white font-bold py-4 rounded-xl hover:bg-red-900 transition-all shadow-lg text-lg">
                            Tambah Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div id="modalEdit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="toggleModal('modalEdit')"></div>

    <!-- Modal Content Center -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="modalEditContent" class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Edit Produk</h3>
                    <button onclick="toggleModal('modalEdit')" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>

                <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Nama produk</label>
                        <input type="text" name="name" id="editName" required 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="category" id="editCategory" required 
                                    class="w-full appearance-none border border-gray-200 rounded-xl px-4 py-3.5 pr-10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                                <option value="Minuman Ringan">Minuman Ringan</option>
                                <option value="Makanan Ringan">Makanan Ringan</option>
                                <option value="Makanan Berat">Makanan Berat</option>
                                <option value="Manual Brew">Manual Brew</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Harga Produk</label>
                        <input type="number" name="price" id="editPrice" required 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Stok Produk</label>
                        <input type="number" name="stock" id="editStock" required min="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>

                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Foto Produk (Biarkan kosong jika tidak diubah) <span class="text-[10px] text-gray-400 font-normal">(Mendukung: webp, svg, png, jpg)</span></label>
                        <div class="flex items-center gap-3">
                            <label class="cursor-pointer bg-[#c5e6fb] hover:bg-blue-200 text-[#0066cc] px-6 py-2 rounded-full font-bold text-sm transition-all shadow-sm border border-blue-100">
                                Choose File
                                <input type="file" name="image" class="hidden" onchange="updateFileName(this, 'fileNameEdit')">
                            </label>
                            <span id="fileNameEdit" class="text-sm text-gray-400 font-medium">No file chosen</span>
                        </div>
                    </div>

                    <div class="form-group flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-[15px] font-bold text-gray-800">Penandaan Best Seller Khusus</label>
                            <p class="text-[11px] text-gray-500 mt-1 max-w-[250px]">Produk ini akan selalu masuk daftar Best Seller secara manual.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="is_best_seller" id="edit_is_best_seller" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#b22a25] text-white font-bold py-4 rounded-xl hover:bg-red-900 transition-all shadow-lg text-lg">
                            Simpan Perubahan
                        </button>
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

    function openEditModal(menu) {
        const form = document.getElementById('editForm');
        form.action = `/admin/menus/${menu.id}`;
        
        document.getElementById('editName').value = menu.name;
        document.getElementById('editCategory').value = menu.category;
        document.getElementById('editPrice').value = menu.price;
        document.getElementById('editStock').value = menu.stock;
        document.getElementById('editStock').value = menu.stock;
        document.getElementById('edit_is_best_seller').checked = menu.is_best_seller == 1;
        document.getElementById('fileNameEdit').innerText = 'No file chosen';
        
        toggleModal('modalEdit');
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

    function toggleStatus(id, isAvailable) {
        fetch(`/admin/menus/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_available: isAvailable ? 1 : 0 })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Status Diperbarui',
                    text: isAvailable ? 'Bahan baku masuk daftar Tersedia.' : 'Bahan baku diset menjadi Habis.',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            } else {
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            location.reload();
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
        if (input) {
            input.value = newStock;
            if (newStock <= 5) {
                input.classList.add('text-red-600');
                input.classList.remove('text-gray-800');
            } else {
                input.classList.remove('text-red-600');
                input.classList.add('text-gray-800');
            }
            input.style.transform = 'scale(1.1)';
            setTimeout(() => input.style.transform = 'scale(1)', 100);
        }
    }

    function applyFilters() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const rows = document.querySelectorAll('.product-row');

        rows.forEach(row => {
            const name = row.dataset.name;
            const cat = row.dataset.category;
            
            const matchKeyword = name.includes(keyword);
            const matchCategory = category === 'all' || cat === category;

            if (matchKeyword && matchCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<style>
    /* Custom scrollbar for table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }
</style>
@endsection
