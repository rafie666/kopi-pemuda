@extends('layouts.kasir')

@section('title', 'Order Menu')

@section('content')
<div class="flex flex-col lg:flex-row w-full h-full overflow-hidden" x-data="{ mobileCartOpen: false }">
    <!-- LEFT COLUMN: MENU GRID -->
    <div class="flex-1 flex flex-col bg-gray-100 overflow-hidden relative lg:border-r border-gray-200">
        <!-- Category Tabs -->
        <div class="px-4 md:px-6 py-3 md:py-4 bg-white shadow-sm z-10">
            <div class="flex gap-2 md:gap-3 overflow-x-auto no-scrollbar pb-1">
                <button onclick="filterCategory('all')" id="cat-all" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-red-600 text-white font-bold shadow-md whitespace-nowrap text-[12px] md:text-sm transition transform hover:scale-105">Semua</button>
                <button onclick="filterCategory('Best Seller')" id="cat-best-seller" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold hover:shadow-md whitespace-nowrap text-[12px] md:text-sm transition"><i class="fas fa-star text-[10px] mr-1"></i>Best Seller</button>
                <button onclick="filterCategory('Makanan Ringan')" id="cat-makanan-ringan" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 whitespace-nowrap text-[12px] md:text-sm transition">Makanan Ringan</button>
                <button onclick="filterCategory('Minuman Ringan')" id="cat-minuman-ringan" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 whitespace-nowrap text-[12px] md:text-sm transition">Minuman Ringan</button>
                <button onclick="filterCategory('Makanan Berat')" id="cat-makanan-berat" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 whitespace-nowrap text-[12px] md:text-sm transition">Makanan Berat</button>
                <button onclick="filterCategory('Manual Brew')" id="cat-manual-brew" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 whitespace-nowrap text-[12px] md:text-sm transition">Manual Brew</button>
                <button onclick="filterCategory('Dessert')" id="cat-dessert" class="category-btn px-4 md:px-5 py-1.5 md:py-2 rounded-full bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 whitespace-nowrap text-[12px] md:text-sm transition">Dessert</button>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                @foreach($menus as $menu)
                <div 
                     data-id="{{ $menu->id }}"
                     data-name="{{ $menu->name }}"
                     data-price="{{ $menu->price }}"
                     data-stock="{{ $menu->stock }}"
                     data-image="{{ asset('storage/' . $menu->image) }}"
                     data-category="{{ $menu->category }}"
                     data-bestseller="{{ $menu->is_best_seller || in_array($menu->id, $topMenuIds) ? 'true' : 'false' }}"
                     class="menu-item bg-white rounded-xl shadow-sm hover:shadow-lg cursor-pointer overflow-hidden transition transform hover:-translate-y-1 group border border-transparent hover:border-red-500 p-3 flex flex-col items-center h-full {{ $menu->stock <= 0 ? 'opacity-50 grayscale pointer-events-none' : '' }}">
                    <!-- Image -->
                    <div class="w-full aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden relative">
                        @if($menu->is_best_seller || in_array($menu->id, $topMenuIds))
                            <div class="absolute top-2 left-2 bg-gradient-to-r from-red-600 to-orange-500 text-white text-[9px] font-black px-2 py-1 rounded-md shadow-md z-10 flex items-center gap-1">
                                <i class="fas fa-star text-[8px]"></i> BEST SELLER
                            </div>
                        @endif
                        @if($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <span class="text-3xl font-bold text-gray-300">{{ substr($menu->name, 0, 1) }}</span>
                        @endif
                        <!-- Add Overlay -->
                        <div class="absolute inset-0 transition duration-300 flex items-center justify-center group-hover:bg-black/10">
                            <span class="bg-white text-black rounded-full p-2 opacity-0 group-hover:opacity-100 transition transform scale-50 group-hover:scale-100 shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </span>
                        </div>
                    </div>
                    <!-- Title & Price -->
                    <h3 class="font-bold text-gray-800 text-center uppercase text-xs mb-1 tracking-wide line-clamp-2 h-8 flex items-center">{{ $menu->name }}</h3>
                    <div class="flex flex-col items-center">
                        <p class="text-red-600 font-bold text-sm">{{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-red-500 font-bold">Stok: {{ $menu->stock }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Floating Cart Button (Mobile Only) -->
        <div class="lg:hidden fixed bottom-6 right-6 z-30">
            <button @click="mobileCartOpen = true" class="bg-red-600 text-white p-4 rounded-full shadow-2xl flex items-center gap-2 hover:bg-red-700 transition transform active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span id="mobile-cart-count" class="bg-white text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
            </button>
        </div>
    </div>

    <!-- RIGHT COLUMN: ORDER MENU (CART) -->
    <div :class="mobileCartOpen ? 'translate-y-0' : 'translate-y-full lg:translate-y-0'"
         class="fixed inset-0 lg:relative lg:inset-auto z-40 lg:z-20 w-full lg:w-[400px] bg-white shadow-xl flex flex-col border-l border-gray-100 transition-transform duration-300 ease-in-out">
        
        <!-- Mobile Cart Header Close Button -->
        <div class="lg:hidden p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-bold">Pesanan Anda</h2>
            <button @click="mobileCartOpen = false" class="p-2 text-gray-500 hover:text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <!-- Header -->
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-2 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <h2 class="text-xl font-bold text-gray-800">Order Menu</h2>
            </div>
           
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50" id="cart-items-container">
            <!-- Empty State -->
            <div id="empty-state" class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p>Belum ada pesanan</p>
            </div>
            <!-- Items will be injected here via JS -->
        </div>

        <!-- Footer / Totals -->
        <div class="p-5 bg-white border-t border-gray-200">
            <div class="flex justify-between items-center text-sm text-gray-500 mb-2">
                <span id="total-items-badge">0 items</span>
                <span>Subtotal</span>
            </div>
            <div class="flex justify-between items-center mb-6">
                <span class="text-2xl font-bold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-gray-900" id="cart-total">Rp 0</span>
            </div>

            <div class="grid grid-cols-1">
                <button onclick="openPaymentModal()" id="btn-pay" disabled class="py-3 rounded-xl font-bold text-white bg-gray-300 cursor-not-allowed transition shadow-lg w-full">
                    Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struk / Receipt (Same as before) -->
<div id="receiptModal" class="fixed inset-0 bg-black/90 z-[60] hidden overflow-y-auto p-4 md:p-10 backdrop-blur-sm">
    <div class="flex min-h-full items-center justify-center">
        <div class="bg-white text-black rounded-[2rem] w-full max-w-sm p-8 shadow-2xl relative my-auto">
        <button onclick="closeReceiptModal()" class="absolute top-4 right-4 text-gray-400 hover:text-black">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold uppercase tracking-widest mb-1">Kopi Pemuda</h2>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Jl. Raya Sindang Barang Loji No. 169-Bogor - (021) 12345</p>
        </div>
        <div class="text-xs text-gray-500 mb-6 border-b border-dashed border-gray-300 pb-4 space-y-1 font-mono">
            <div class="flex justify-between"><span>TGL:</span> <span id="receipt-date">--/--/--</span></div>
            <div class="flex justify-between"><span>KSR:</span> <span>{{ Auth::user()->name }}</span></div>
            <div class="flex justify-between"><span>MET:</span> <span id="receipt-method">TUNAI</span></div>
        </div>
        <div id="receipt-items" class="text-sm mb-6 space-y-2 font-mono"></div>
        <div class="border-t border-dashed border-gray-400 pt-4 mb-4 font-mono">
            <div class="flex justify-between text-sm mb-1"><span>SUBTOTAL</span><span id="receipt-subtotal">Rp 0</span></div>
            <div class="flex justify-between text-sm mb-1 text-red-600"><span>DISKON</span><span id="receipt-discount">Rp 0</span></div>
            <div class="flex justify-between font-bold text-xl mb-2"><span>TOTAL</span><span id="receipt-total">Rp 0</span></div>
            <div class="flex justify-between text-xs text-gray-500"><span>TUNAI</span><span id="receipt-cash">Rp 0</span></div>
            <div class="flex justify-between text-xs text-gray-500"><span>KEMBALI</span><span id="receipt-change">Rp 0</span></div>
        </div>
        <div class="text-center text-xs text-gray-400 mb-6 uppercase tracking-widest">
            <p>Terima Kasih</p><p>Selamat Datang Kembali</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition">CETAK STRUK</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal Payment (Simplified/Reused) -->
<!-- Modal Payment (Restored Layout) -->
<div id="paymentModal" class="fixed inset-0 bg-white z-50 hidden flex flex-col text-gray-900">
    <div class="p-4 border-b border-gray-200 flex items-center gap-4">
        <button onclick="closePaymentModal()" class="text-2xl font-bold text-gray-500 hover:text-black">&larr;</button>
        <h2 class="text-xl font-semibold">Pembayaran</h2>
    </div>

    <div class="flex flex-1 h-full overflow-hidden">
        <!-- Left: Order Details (Restored) -->
        <div class="w-1/3 border-r border-gray-200 p-6 hidden md:flex flex-col bg-gray-50">
            <h3 class="text-gray-500 text-sm font-semibold mb-4 uppercase tracking-wider">Dine In</h3>
            <div id="payment-order-list" class="flex-1 overflow-y-auto pr-2 space-y-4">
                <!-- Items injected by renderPaymentList() -->
            </div>
            <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between items-end">
                <span class="text-gray-600">Total</span>
                <span class="font-bold text-2xl text-gray-900" id="payment-subtotal">Rp 0</span>
            </div>
        </div>

        <!-- Right: Payment Interface -->
        <div class="flex-1 p-6 flex flex-col items-center max-w-2xl mx-auto w-full bg-white overflow-y-auto scrollbar-hide py-10">
            <div class="text-center mb-8">
                <p class="text-gray-500 mb-2 uppercase text-sm tracking-widest">Total Pembayaran</p>
                <h1 class="text-5xl font-bold text-gray-900" id="payment-total-big">Rp 0</h1>
            </div>
            <div class="flex w-full bg-gray-100 rounded-lg p-1.5 mb-8 max-w-md">
                <button onclick="setPaymentMethod('cash')" id="btn-cash" class="flex-1 py-3 rounded-md bg-white text-gray-900 font-bold shadow-md transition transform border border-gray-200">Tunai</button>
                <button onclick="setPaymentMethod('mbanking')" id="btn-mbanking" class="flex-1 py-3 rounded-md text-gray-500 hover:text-gray-900 font-semibold transition">Non-Tunai (Transfer)</button>
            </div>

            <!-- Discount Section -->
            <div class="w-full max-w-md mb-8">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-gray-500 text-xs uppercase tracking-wider font-bold">Potongan Harga (Discount)</label>
                    <div class="flex bg-gray-100 rounded-lg p-0.5 text-[10px] font-bold">
                        <button onclick="setDiscountType('rp')" id="type-rp" class="px-3 py-1 rounded-md bg-white shadow-sm transition">Rp</button>
                        <button onclick="setDiscountType('percent')" id="type-percent" class="px-3 py-1 rounded-md text-gray-400 hover:text-gray-600 transition">%</button>
                    </div>
                </div>
                <div class="relative">
                    <span id="discount-symbol" class="absolute left-0 bottom-3 text-gray-400 text-lg transition-all duration-300">Rp</span>
                    <input type="number" id="input-discount" class="w-full bg-transparent border-b border-gray-300 pl-8 py-2 text-xl text-gray-900 focus:border-red-600 outline-none placeholder-gray-300 transition [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="0" oninput="updatePaymentCalculations()" min="0">
                </div>
            </div>

            <div id="cash-section" class="w-full max-w-md space-y-6 mb-10">
                <div>
                    <label class="block text-gray-500 text-xs uppercase tracking-wider mb-2">Jumlah Pembayaran</label>
                    <input type="number" id="input-payment" class="w-full bg-transparent border-b border-gray-300 py-3 text-3xl text-gray-900 focus:border-red-600 outline-none placeholder-gray-400 transition [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="Rp 0" oninput="calculateChange()">
                </div>
                <div>
                    <label class="block text-gray-500 text-xs uppercase tracking-wider mb-2">Kembalian</label>
                    <div class="w-full bg-transparent border-b border-gray-300 py-3 text-2xl text-gray-900 font-mono" id="payment-change">Rp 0</div>
                </div>
            </div>
            <div id="mbanking-section" class="w-full max-w-md mb-10 hidden flex flex-col items-center p-6 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="mb-6 flex flex-col items-center w-full">
                    <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest mb-4">Transfer Via</span>
                    
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-center w-full max-w-[200px] h-20 mb-6 group transition-all">
                        <span class="text-4xl font-black italic text-[#0066AE] tracking-tighter">BCA</span>
                    </div>

                    <div class="text-center w-full bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-2">
                        <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Nomor Rekening</p>
                        <p class="text-3xl font-mono font-bold text-gray-800 tracking-widest" id="copy-rek">7360194013</p>
                        <div class="h-px bg-gray-100 w-1/2 mx-auto my-2"></div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-tighter">HENDRA BONAVASIUS SINAGA</p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-100 p-4 rounded-xl w-full flex items-center gap-3">
                    <div class="bg-red-500 text-white p-2 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-[11px] text-red-700 font-medium leading-relaxed">Harap lampirkan bukti transfer setelah pembayaran dilakukan untuk diverifikasi oleh kasir.</p>
                </div>
            </div>
            <div class="flex gap-4 w-full max-w-md">
                <button onclick="closePaymentModal()" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 py-4 rounded-xl font-bold transition">Cancel</button>
                <button onclick="processPayment()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-4 rounded-xl font-bold shadow-lg transition transform hover:-translate-y-1">Proses</button>
            </div>
        </div>
    </div>
</div>


<script>
    let cart = [];
    let totalPrice = 0;
    let currentCategory = 'all';
    let discountType = 'rp'; // Variable to track discount type

    // Safeguard for menus with special chars
    // We are using event listeners to avoid inline string escaping issues.

    document.addEventListener('DOMContentLoaded', () => {
        // Search and Filtering
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            document.querySelectorAll('.menu-item').forEach(item => {
                const name = item.querySelector('h3').innerText.toLowerCase();
                const category = item.dataset.category; // Ensure dataset is accessed correctly
                const isBestSeller = item.getAttribute('data-bestseller') === 'true';
                
                // If simple search by name
                if (name.includes(keyword)) {
                   // Only show if it also matches the active category
                   if (currentCategory === 'all') {
                       item.style.display = 'flex';
                   } else if (currentCategory === 'Best Seller') {
                       item.style.display = isBestSeller ? 'flex' : 'none';
                   } else {
                       item.style.display = (category === currentCategory) ? 'flex' : 'none';
                   }
                } else {
                   item.style.display = 'none';
                }
            });
            // If empty, revert to category filtering instead of showing everything
            if (keyword === '') filterCategory(currentCategory);
        });

        // Global click handler for menu items to avoid inline JS issues
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = Number(this.dataset.price);
                const stock = Number(this.dataset.stock);
                const image = this.dataset.image;
                addToCartDirect(id, name, price, image, stock);
            });
        });
    });

    function filterCategory(category) {
        currentCategory = category;
        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-red-600', 'text-white', 'scale-105');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        
        let activeId = 'cat-' + category.toLowerCase().replace(/\s+/g, '-');
        if(category === 'all') activeId = 'cat-all';
        const activeBtn = document.getElementById(activeId);
        if(activeBtn) {
            activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
            activeBtn.classList.add('bg-red-600', 'text-white', 'scale-105');
        }

        document.querySelectorAll('.menu-item').forEach(item => {
            const itemCat = item.getAttribute('data-category');
            const isBestSeller = item.getAttribute('data-bestseller') === 'true';
            
            if (category === 'all') {
                item.style.display = 'flex';
            } else if (category === 'Best Seller') {
                item.style.display = isBestSeller ? 'flex' : 'none';
            } else if (itemCat === category) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Direct Add To Cart
    function addToCartDirect(id, name, price, imageSrc, stock) {
        // Ensure ID is number
        id = Number(id);
        const existing = cart.find(i => i.id === id);
        if(existing) {
            if (existing.qty >= stock) {
                Swal.fire({ icon: 'warning', title: 'Stok Terbatas', text: 'Tidak bisa menambah lebih dari stok yang ada.' });
                return;
            }
            existing.qty += 1;
        } else {
            if (stock <= 0) return;
            cart.push({ id, name, price, image: imageSrc, qty: 1, note: '', stock: stock });
        }
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyState = document.getElementById('empty-state');
        const totalEl = document.getElementById('cart-total');
        const btnPay = document.getElementById('btn-pay');
        const badge = document.getElementById('total-items-badge');

        if(cart.length === 0) {
            container.innerHTML = '';
            if(emptyState) {
                container.appendChild(emptyState);
                emptyState.style.display = 'flex';
            }
            totalEl.innerText = 'Rp 0';
            btnPay.setAttribute('disabled', 'true');
            btnPay.classList.add('bg-gray-300', 'cursor-not-allowed');
            btnPay.classList.remove('bg-black', 'hover:bg-gray-800', 'text-white', 'bg-red-600', 'hover:bg-red-700');
            badge.innerText = '0 items';
            document.getElementById('mobile-cart-count').innerText = '0';
            return;
        }

        if(emptyState) emptyState.style.display = 'none';
        container.innerHTML = '';
        
        cart.forEach((item, index) => {
            container.innerHTML += `
                <div class="bg-white border text-gray-800 border-gray-200 rounded-xl p-3 shadow-sm flex gap-3 relative overflow-hidden">
                   <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0">
                        <img src="${item.image}" class="w-full h-full object-cover rounded-lg">
                   </div>
                   <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-sm line-clamp-1" title="${item.name}">${item.name}</h4>
                            <p class="text-xs text-gray-500">${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex justify-between items-end mt-1">
                             <input type="text" placeholder="Catatan..." class="bg-transparent text-xs border-b border-gray-200 w-24 outline-none focus:border-red-500" value="${item.note}" onchange="updateNote(${index}, this.value)">
                             
                             <div class="flex items-center bg-gray-100 rounded-lg">
                                 <button onclick="updateCartItem(${index}, -1)" class="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-red-500 font-bold">-</button>
                                 <input type="number" min="0" value="${item.qty}" onchange="changeQty(${index}, this.value)" 
                                    class="w-10 text-center text-xs font-bold bg-transparent border-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                 <button onclick="updateCartItem(${index}, 1)" class="w-6 h-6 flex items-center justify-center text-gray-600 hover:text-green-500 font-bold">+</button>
                             </div>
                        </div>
                   </div>
                </div>
            `;
        });

        // Safe recalculation
        totalPrice = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const totalQty = cart.reduce((acc, item) => acc + item.qty, 0);

        totalEl.innerText = formatRupiah(totalPrice);
        badge.innerText = totalQty + ' items';
        
        btnPay.removeAttribute('disabled');
        btnPay.classList.remove('bg-gray-300', 'cursor-not-allowed');
        btnPay.classList.add('bg-red-600', 'hover:bg-red-700', 'text-white');

        // Update mobile badge
        document.getElementById('mobile-cart-count').innerText = totalQty;
        
        // Auto close mobile cart if empty
        if(cart.length === 0) {
            mobileCartOpen = false;
        }
    }

    function updateCartItem(index, change) {
        if (cart[index]) {
            let newQty = cart[index].qty + change;
            if (newQty <= 0) {
                 cart.splice(index, 1);
            } else {
                if (newQty > cart[index].stock) {
                    Swal.fire({ icon: 'warning', title: 'Stok Terbatas', text: 'Stok tidak mencukupi.' });
                    return;
                }
                cart[index].qty = newQty;
            }
            // Update both views
            renderCart();
            
            // If payment modal is open, update that list too
            if(!document.getElementById('paymentModal').classList.contains('hidden')) {
                if(cart.length === 0) {
                    closePaymentModal();
                } else {
                    renderPaymentList(); // Restore function call
                    updatePaymentCalculations();
                }
            }
        }
    }

    function updateNote(index, val) {
        if(cart[index]) cart[index].note = val;
    }

    function changeQty(index, val) {
        let newQty = parseInt(val);
        if (isNaN(newQty) || newQty <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].qty = newQty;
        }
        renderCart();
        if(!document.getElementById('paymentModal').classList.contains('hidden')) {
            renderPaymentList();
            updatePaymentCalculations();
        }
    }

    // Payment Logic
    function openPaymentModal() {
        if (cart.length === 0) return;
        renderPaymentList(); // Ensure list is rendered
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('input-discount').value = '';
        updatePaymentCalculations();
        document.getElementById('input-payment').value = ''; 
        document.getElementById('payment-change').innerText = 'Rp 0';
    }

    // Restore renderPaymentList with interactive buttons (Step 36 logic)
    function renderPaymentList() {
        const list = document.getElementById('payment-order-list'); // Need to ensure ID matches HTML
        // In previous step (60), the ID in the simplified modal was 'payment-order-list' (line 130 of replaced content, wait. Line 133 of file view Step 60 is inside paymentModal? No wait.)
        // Let's check view_file Step 74.
        // Line 128: <div id="paymentModal"...>
        // Line 133: <div class="flex-1 p-6... >
        // Wait, where is the Left Column in Step 74's payment modal?
        // It seems Step 60's replacement content for Payment Modal was "Simplified/Reused" and MIGHT HAVE REMOVED THE LEFT COLUMN (LIST)?
        // Let's check Step 74 Lines 128-166.
        // YES! The Left Column with `payment-order-list` is MISSING in the current file!
        // That explains "gabisa mengurangi dan menambah" in payment (because the list isn't there!).
        // I need to RESTORE the Left Column in the HTML first, then the JS.
        
        // JS part (will fail if element missing, but I will fix HTML in next tool call or same if possible. 
        // Cannot replace non-contiguous HTML and JS in one replace_file_content efficiently if they are far apart.
        // JS is at bottom. HTML is Middle.
        // I should use `renderPaymentList` safely here, assuming I will fix HTML.
        
        if(!list) return; // Guard
        
        list.innerHTML = '';
        cart.forEach((item, index) => {
            list.innerHTML += `
                <div class="flex justify-between items-center border-b border-gray-200 pb-2 last:border-0 hover:bg-gray-100 p-2 rounded transition group">
                    <div class="flex-1">
                        <div class="text-gray-800 font-medium text-lg">${item.name}</div>
                        <div class="text-gray-500 text-xs">${item.note ? '"'+item.note+'"' :     ''}</div>
                        <div class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                             <span class="text-xs text-gray-400">@ ${formatRupiah(item.price)}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-2">
                        <div class="text-gray-800 font-mono font-bold">${formatRupiah(item.price * item.qty)}</div>
                        
                        <div class="flex items-center bg-white rounded-md shadow-sm border border-gray-200">
                             <button onclick="updateCartItem(${index}, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-l-md transition">-</button>
                             <input type="number" min="0" value="${item.qty}" onchange="changeQty(${index}, this.value)" 
                                class="w-10 text-center text-sm font-bold text-gray-700 bg-transparent border-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                             <button onclick="updateCartItem(${index}, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-green-500 hover:bg-green-50 rounded-r-md transition">+</button>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    function updatePaymentCalculations() {
        totalPrice = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const inputValue = Number(document.getElementById('input-discount').value) || 0;
        
        let discountAmount = 0;
        if (discountType === 'percent') {
            discountAmount = (totalPrice * inputValue) / 100;
        } else {
            discountAmount = inputValue;
        }

        const subEl = document.getElementById('payment-subtotal');
        if(subEl) subEl.innerText = formatRupiah(totalPrice);
        
        const payable = totalPrice - discountAmount;
        document.getElementById('payment-total-big').innerText = formatRupiah(payable < 0 ? 0 : payable);
        
        if(document.getElementById('input-payment').value) {
            calculateChange();
        }
    }

    function setDiscountType(type) {
        discountType = type;
        const btnRp = document.getElementById('type-rp');
        const btnPercent = document.getElementById('type-percent');
        const symbol = document.getElementById('discount-symbol');

        if (type === 'rp') {
            btnRp.classList.add('bg-white', 'shadow-sm');
            btnRp.classList.remove('text-gray-400');
            btnPercent.classList.remove('bg-white', 'shadow-sm');
            btnPercent.classList.add('text-gray-400');
            symbol.innerText = 'Rp';
            symbol.style.left = '0';
        } else {
            btnPercent.classList.add('bg-white', 'shadow-sm');
            btnPercent.classList.remove('text-gray-400');
            btnRp.classList.remove('bg-white', 'shadow-sm');
            btnRp.classList.add('text-gray-400');
            symbol.innerText = '%';
            // Adjust position for % symbol if needed, or keep it left
            symbol.style.left = '0';
        }
        updatePaymentCalculations();
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function calculateChange() {
        const pay = document.getElementById('input-payment').value;
        const inputValue = Number(document.getElementById('input-discount').value) || 0;
        let discountAmount = 0;
        if (discountType === 'percent') {
            discountAmount = (totalPrice * inputValue) / 100;
        } else {
            discountAmount = inputValue;
        }
        const payable = totalPrice - discountAmount;
        const change = pay - payable;
        document.getElementById('payment-change').innerText = formatRupiah(change < 0 ? 0 : change);
    }

    let currentPaymentMethod = 'cash';
    function setPaymentMethod(method) {
        currentPaymentMethod = method;
        const btnCash = document.getElementById('btn-cash');
        const btnMbanking = document.getElementById('btn-mbanking');
        const cashSection = document.getElementById('cash-section');
        const mbankingSection = document.getElementById('mbanking-section');

        if(method === 'cash') {
            btnCash.classList.add('bg-white', 'text-gray-900', 'font-bold', 'shadow-md', 'border', 'border-gray-200');
            btnCash.classList.remove('text-gray-500');
            btnMbanking.classList.remove('bg-white', 'text-gray-900', 'font-bold', 'shadow-md', 'border');
            btnMbanking.classList.add('text-gray-500');
            cashSection.classList.remove('hidden');
            mbankingSection.classList.add('hidden');
            document.getElementById('input-payment').focus();
        } else {
            btnMbanking.classList.add('bg-white', 'text-gray-900', 'font-bold', 'shadow-md', 'border', 'border-gray-200');
            btnMbanking.classList.remove('text-gray-500');
            btnCash.classList.remove('bg-white', 'text-gray-900', 'font-bold', 'shadow-md', 'border');
            btnCash.classList.add('text-gray-500');
            cashSection.classList.add('hidden');
            mbankingSection.classList.remove('hidden');
        }
    }

    async function processPayment() {
        const inputValue = Number(document.getElementById('input-discount').value) || 0;
        let discountAmount = 0;
        if (discountType === 'percent') {
            discountAmount = (totalPrice * inputValue) / 100;
        } else {
            discountAmount = inputValue;
        }
        const payable = totalPrice - discountAmount;

        let pay;
        if (currentPaymentMethod === 'cash') {
            pay = Number(document.getElementById('input-payment').value);
            if(pay < payable) {
                Swal.fire({
                    icon: 'error',
                    title: 'Uang Kurang!',
                    text: 'Jumlah pembayaran tidak mencukupi.',
                });
                return;
            }
        } else {
            pay = payable; 
        }

        const transactionData = {
            total_price: totalPrice,
            discount_amount: discountAmount,
            payment_method: currentPaymentMethod,
            items: cart.map(item => ({
                id: item.id,
                quantity: item.qty,
                price: item.price
            }))
        };

        try {
            const response = await fetch("{{ route('kasir.transaksi.store') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify(transactionData)
            });

            const result = await response.json();
            if(result.status === 'success') {
                if (result.snap_token) {
                    window.snap.pay(result.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire({ icon: 'success', title: 'Pembayaran Berhasil!', text: 'Pesanan telah diproses.' });
                            const inputValue = Number(document.getElementById('input-discount').value) || 0;
                            let discAmt = discountType === 'percent' ? (totalPrice * inputValue / 100) : inputValue;
                            showReceipt(pay, totalPrice - discAmt, discAmt);
                        },
                        onPending: function(result) {
                            Swal.fire({ icon: 'info', title: 'Menunggu Pembayaran', text: 'Silahkan selesaikan pembayaran.' });
                            closePaymentModal();
                            cart = [];
                            renderCart();
                        },
                        onError: function(result) {
                            Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Terjadi kesalahan saat pembayaran.' });
                        },
                        onClose: function() {
                            Swal.fire({ icon: 'warning', title: 'Pembayaran Dibatalkan', text: 'Anda menutup jendela pembayaran.' });
                        }
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Transaksi Berhasil!',
                    text: 'Pesanan telah diproses.',
                    timer: 2000,
                    showConfirmButton: false
                });
                const inputValue = Number(document.getElementById('input-discount').value) || 0;
                let discAmt = discountType === 'percent' ? (totalPrice * inputValue / 100) : inputValue;
                showReceipt(pay, totalPrice - discAmt, discAmt);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.message,
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem.',
            });
        }
    }

    function showReceipt(pay, payable, discount) {
        const today = new Date();
        document.getElementById('receipt-date').innerText = today.toLocaleDateString() + ' ' + today.toLocaleTimeString();
        document.getElementById('receipt-method').innerText = currentPaymentMethod.toUpperCase();
        
        const list = document.getElementById('receipt-items');
        list.innerHTML = '';
        cart.forEach(item => {
            list.innerHTML += `
                <div class="flex justify-between"><span>${item.name}</span><span>${formatRupiah(item.price * item.qty)}</span></div>
                <div class="flex justify-between"><span class="text-xs text-gray-500 mb-1 ml-2">x${item.qty} @ ${formatRupiah(item.price)}</span></div>
            `;
        });

        const inputValue = Number(document.getElementById('input-discount').value) || 0;
        let calculatedDiscount = 0;
        if (discountType === 'percent') {
            calculatedDiscount = (totalPrice * inputValue) / 100;
        } else {
            calculatedDiscount = inputValue;
        }

        document.getElementById('receipt-total').innerText = formatRupiah(totalPrice - calculatedDiscount);
        document.getElementById('receipt-subtotal').innerText = formatRupiah(totalPrice);
        document.getElementById('receipt-discount').innerText = '- ' + formatRupiah(calculatedDiscount);
        document.getElementById('receipt-cash').innerText = formatRupiah(pay);
        document.getElementById('receipt-change').innerText = formatRupiah(pay - (totalPrice - calculatedDiscount));

        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('receiptModal').classList.remove('hidden');
    }

    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
        cart = [];
        renderCart();
        // Reload to update stock counts in UI
        location.reload();
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }
</script>
@endsection
