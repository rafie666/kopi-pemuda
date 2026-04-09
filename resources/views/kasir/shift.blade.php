@extends('layouts.kasir')

@section('title', 'Manajemen Shift')

@section('content')
<div class="flex-1 overflow-y-auto bg-gray-50 flex flex-col justify-center items-center p-6">
    <div class="max-w-md w-full">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(!$activeShift)
            <!-- Form Mulai Shift -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-red-600 p-6 text-center text-white">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h2 class="text-2xl font-bold">Mulai Shift Baru</h2>
                    <p class="text-red-100 text-sm mt-1">Masukkan data awal untuk memulai hari ini</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('kasir.shift.start') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Saldo Awal (Modal Laci)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-bold">Rp</span>
                                <input type="number" name="cash_start" required min="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-gray-800 text-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="0">
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Jumlah uang tunai fisik yang ada di laci saat ini.</p>
                        </div>
                        <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mulai Transaksi
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Kelola Shift Aktif -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-black p-6 text-center text-white relative">
                    <div class="absolute top-4 right-4 flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full text-xs font-bold font-mono">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        AKTIF
                    </div>
                    <h2 class="text-2xl font-bold mt-2">Shift Saat Ini</h2>
                    <p class="text-gray-400 text-sm mt-1">Mulai: {{ $activeShift->start_time->format('d M Y, H:i') }}</p>
                </div>
                
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-bold uppercase tracking-wider">Saldo Awal</span>
                            <span class="text-gray-800 font-bold">Rp {{ number_format($activeShift->cash_start, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-bold uppercase tracking-wider">Pemasukan Tunai</span>
                            <span class="text-green-600 font-bold">+ Rp {{ number_format($cashTransactions, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-dashed border-gray-300 pt-4 flex justify-between items-center">
                            <span class="text-gray-800 font-black uppercase tracking-wider">Estimasi di Laci</span>
                            <span class="text-xl font-bold text-black border-b-2 border-black">Rp {{ number_format($expectedCash, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('kasir.shift.end') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-wide">Uang Tunai Real (Fisik Laci Akhir)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-bold">Rp</span>
                                <input type="number" name="cash_end" required min="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 text-gray-800 text-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="0">
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Hitung jumlah fisik uang di laci secara teliti sebelum mengakhiri shift.</p>
                        </div>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('kasir.order') }}" class="flex-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-4 rounded-xl transition text-center shadow-sm">
                                Kembali Order
                            </a>
                            <button type="submit" onclick="return confirm('Anda yakin ingin mengakhiri shift? Periksa uang dengan teliti!')" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Akhiri Shift
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
