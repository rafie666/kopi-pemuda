<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'nullable',
        ]);

        Pengeluaran::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category' => $request->category ?: 'Operasional',
        ]);

        $this->logActivity('mencatat pengeluaran: ' . $request->description . ' sejumlah Rp ' . number_format($request->amount, 0, ',', '.'));

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $desc = $pengeluaran->description;
        $amount = $pengeluaran->amount;
        $pengeluaran->delete();
        
        $this->logActivity('menghapus pengeluaran: ' . $desc . ' sejumlah Rp ' . number_format($amount, 0, ',', '.'));
        
        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
