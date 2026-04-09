<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Transaksi::with(['user', 'details.menu']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $transaksis = $query->latest()->get();
        return view('admin.laporan.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = \App\Models\Transaksi::with(['user', 'details.menu'])->findOrFail($id);
        return view('admin.laporan.struk', compact('transaksi'));
    }
}
