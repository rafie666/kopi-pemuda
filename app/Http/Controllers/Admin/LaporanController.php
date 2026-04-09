<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        $transaksis = \App\Models\Transaksi::with(['user', 'details.menu'])->latest()->get();
        return view('admin.laporan.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = \App\Models\Transaksi::with(['user', 'details.menu'])->findOrFail($id);
        return view('admin.laporan.struk', compact('transaksi'));
    }
}
