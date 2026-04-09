@extends('layouts.kasir')

@section('title', 'Shift Saya')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Informasi Shift</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <div class="mb-4">
                <span class="block text-gray-500 text-sm uppercase">Nama Kasir</span>
                <span class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</span>
            </div>
            <div class="mb-4">
                <span class="block text-gray-500 text-sm uppercase">Waktu Mulai Shift</span>
                <span class="text-xl text-green-600 font-mono">-- : --</span>
            </div>
            <div class="mb-4">
                <span class="block text-gray-500 text-sm uppercase">Durasi Kerja</span>
                <span class="text-xl text-gray-800 font-mono">0 Jam 0 Menit</span>
            </div>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-700">Tutup Shift</h3>
            <p class="text-sm text-gray-600 mb-4">
                Pastikan semua transaksi telah selesai sebelum menutup shift.
                Saldo akhir akan dihitung berdasarkan transaksi yang tercatat sistem.
            </p>
            <form action="#" method="POST"> <!-- Route not implemented yet -->
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-1">Uang Tunai (Hitung Manual)</label>
                    <input type="number" class="w-full border p-2 rounded" placeholder="Masukkan jumlah uang di laci">
                </div>
                <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded" onclick="alert('Fitur tutup shift belum aktif')">
                    Akhiri Shift
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
