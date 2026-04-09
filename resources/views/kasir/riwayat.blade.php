@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="p-6 bg-gray-50 flex-1 overflow-y-auto">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Transaksi
            </h2>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <form action="{{ route('kasir.transaksi.index') }}" method="GET" class="flex-1 md:w-64 flex relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, Kasir, dsb..." class="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-lg outline-none focus:border-red-500 text-sm">
                    <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
                <a href="{{ route('kasir.order') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="hidden md:inline">Kembali</span>
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100 uppercase text-xs text-gray-500 tracking-wider">
                        <tr>
                            <th class="p-4">Waktu</th>
                            <th class="p-4">ID Transaksi</th>
                            <th class="p-4">Kasir</th>
                            <th class="p-4">Item (Qty)</th>
                            <th class="p-4">Diskon</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Metode</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="p-4 whitespace-nowrap text-gray-600">
                                <div class="font-medium text-gray-800">{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="text-xs">{{ $trx->created_at->format('H:i') }}</div>
                            </td>
                            <td class="p-4 font-mono font-bold text-gray-700">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-[10px] shadow-sm">
                                        {{ substr($trx->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-800 text-xs">{{ $trx->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-gray-600 space-y-1">
                                @foreach($trx->details as $detail)
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-gray-800 w-4">{{ $detail->quantity }}x</span>
                                    <span class="truncate">{{ $detail->menu->name ?? 'Menu Dihapus' }}</span>
                                </div>
                                @endforeach
                            </td>
                            <td class="p-4 text-red-500 font-medium">
                                {{ $trx->discount_amount > 0 ? 'Rp ' . number_format($trx->discount_amount, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-4 font-bold text-gray-900 text-lg">Rp {{ number_format($trx->payable_amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 bg-gray-100 border border-gray-200 rounded text-[10px] font-bold text-gray-600 uppercase tracking-wider">{{ $trx->payment_method }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 {{ $trx->status == 'completed' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }} border rounded text-[10px] font-bold uppercase tracking-wider">{{ $trx->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada transaksi hari ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transaksis->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $transaksis->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
