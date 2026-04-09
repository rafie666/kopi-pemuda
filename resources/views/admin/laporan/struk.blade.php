<div class="p-4 bg-white max-w-sm mx-auto font-mono text-sm">
    <div class="text-center mb-4">
        <h3 class="font-bold text-xl uppercase tracking-widest">Kopi Pemuda</h3>
        <p class="text-xs">Jl. Pemuda No. 123, Kota Kopi</p>
        <p class="text-xs">Telp: 0812-3456-7890</p>
    </div>

    <div class="border-b-2 border-dashed border-gray-400 mb-4 pb-2">
        <div class="flex justify-between">
            <span>No. Transaksi</span>
            <span>#{{ $transaksi->id }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tanggal</span>
            <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Kasir</span>
            <span>{{ $transaksi->user->name ?? 'Kasir' }}</span>
        </div>
    </div>

    <div class="mb-4">
        @foreach($transaksi->details as $detail)
        <div class="mb-2">
            <div class="font-bold">{{ $detail->menu->name ?? 'Item Terhapus' }}</div>
            <div class="flex justify-between">
                <span>{{ $detail->quantity }} x {{ number_format($detail->menu->price ?? 0, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="border-t-2 border-dashed border-gray-400 pt-2 mb-4 space-y-1">
        <div class="flex justify-between text-xs">
            <span>Subtotal</span>
            <span>Rp {{ number_format($transaksi->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-xs text-red-600">
            <span>Diskon</span>
            <span>- Rp {{ number_format($transaksi->discount_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg mt-2 border-t border-dashed border-gray-200 pt-2">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->payable_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-xs mt-2">
            <span>Metode</span>
            <span class="uppercase font-bold">{{ $transaksi->payment_method }}</span>
        </div>
    </div>

    <div class="text-center text-xs mt-6">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>#KopiPemudaUntukSemua</p>
    </div>
</div>
