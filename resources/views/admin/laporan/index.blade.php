@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Riwayat Transaksi</h2>
                <p class="text-sm text-gray-400 font-medium mt-1">Daftar semua transaksi yang telah selesai di Kopi Pemuda.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto rounded-xl">
            <table class="w-full text-left border-collapse" id="laporanTable">
                <thead>
                    <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[11px]">
                        <th class="py-5 px-6 font-bold text-gray-400">ID Transaksi</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Tanggal & Waktu</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Kasir</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Metode</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Total</th>
                        <th class="py-5 px-6 font-bold text-gray-400">Status</th>
                        <th class="py-5 px-6 font-bold text-gray-400 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="py-5 px-6 font-black text-gray-800">#{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-6 text-gray-500 font-medium">{{ $transaksi->created_at->format('d M Y') }} <span class="text-gray-300 text-xs ml-1">{{ $transaksi->created_at->format('H:i') }}</span></td>
                        <td class="py-5 px-6 text-gray-700 font-bold uppercase text-xs tracking-wide">{{ $transaksi->user->name ?? 'System' }}</td>
                        <td class="py-5 px-6">
                            @if($transaksi->payment_method == 'qris')
                                <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase">QRIS</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase">CASH</span>
                            @endif
                        </td>
                        <td class="py-5 px-6 font-black text-gray-800 text-base">Rp {{ number_format($transaksi->payable_amount, 0, ',', '.') }}</td>
                        <td class="py-5 px-6">
                            <span class="bg-gray-50 text-gray-500 border border-gray-100 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase">Selesai</span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex justify-center">
                                <button onclick="showReceipt({{ $transaksi->id }})" 
                                        class="flex items-center gap-2 border border-gray-200 hover:border-gray-800 px-5 py-2 rounded-xl transition-all text-gray-700 font-black text-xs bg-white shadow-sm hover:scale-105 active:scale-95">
                                    Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($transaksis->isEmpty())
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i class="fas fa-receipt text-4xl text-gray-100"></i>
                                <p class="text-gray-400 font-bold italic">Belum ada data transaksi yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Struk -->
    <div id="receiptModal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-white/30 backdrop-blur-md transition-opacity duration-300" onclick="closeReceiptModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="receiptModalContent" class="relative w-full max-w-sm transform overflow-hidden rounded-[2rem] bg-white shadow-2xl transition-all duration-300 scale-95 opacity-0 border border-gray-100">
                <div class="flex justify-between items-center px-8 py-6 border-b border-gray-50">
                    <h3 class="font-black text-gray-800">Detail Transaksi</h3>
                    <button onclick="closeReceiptModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
                <div id="receiptContent" class="max-h-[70vh] overflow-y-auto bg-white">
                    <!-- Content loaded via AJAX -->
                    <div class="flex justify-center py-20">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-red-50 border-t-red-600"></div>
                    </div>
                </div>
                <div class="p-8 border-t border-gray-50 flex flex-col gap-3">
                    <button onclick="printReceipt()" class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-[#b91c1c] text-white rounded-2xl hover:bg-red-800 transition-all font-black text-sm shadow-xl shadow-red-500/20 active:scale-[0.98]">
                        <i class="fas fa-print"></i> Cetak Struk
                    </button>
                    <button onclick="closeReceiptModal()" class="w-full px-6 py-4 bg-gray-50 text-gray-500 rounded-2xl hover:bg-gray-100 transition-all font-bold text-sm active:scale-[0.98]">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
    function showReceipt(id) {
        const modal = document.getElementById('receiptModal');
        const content = document.getElementById('receiptModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        const contentDiv = document.getElementById('receiptContent');
        
        // Reset content to loading state
        contentDiv.innerHTML = '<div class="flex justify-center py-8"><svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';

        // Fetch receipt content
        fetch(`/admin/laporan/${id}`)
            .then(response => response.text())
            .then(html => {
                contentDiv.innerHTML = html;
            })
            .catch(error => {
                contentDiv.innerHTML = '<div class="text-center py-8 text-red-500">Gagal memuat struk.</div>';
                console.error('Error:', error);
            });
    }

    function closeReceiptModal() {
        const modal = document.getElementById('receiptModal');
        const content = document.getElementById('receiptModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function printReceipt() {
        const content = document.getElementById('receiptContent').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=400');
        printWindow.document.write('<html><head><title>Cetak Struk</title>');
        printWindow.document.write('<script src="https://unpkg.com/@tailwindcss/browser@4"><\/script>');
        printWindow.document.write('</head><body onload="window.print();window.close()">');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
    }
</script>
@endsection
