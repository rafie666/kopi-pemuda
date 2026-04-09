<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())->whereNull('end_time')->first();
        
        $expectedCash = 0;
        $cashTransactions = 0;

        if ($activeShift) {
            $cashTransactions = \App\Models\Transaksi::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('created_at', '>=', $activeShift->start_time)
                ->where('payment_method', 'cash')
                ->sum('payable_amount');
            
            $expectedCash = $activeShift->cash_start + $cashTransactions;
        }

        return view('kasir.shift', compact('activeShift', 'expectedCash', 'cashTransactions'));
    }

    public function startShift(Request $request)
    {
        $request->validate([
            'cash_start' => 'required|numeric|min:0'
        ]);

        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())->whereNull('end_time')->first();
        if ($activeShift) {
            return back()->with('error', 'Anda sudah memiliki shift yang aktif.');
        }

        \App\Models\Shift::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'start_time' => now(),
            'cash_start' => $request->cash_start,
        ]);

        $this->logActivity('memulai shift dengan saldo awal Rp ' . number_format($request->cash_start, 0, ',', '.'));

        return redirect()->route('kasir.order')->with('success', 'Shift berhasil dimulai.');
    }

    public function endShift(Request $request)
    {
        $request->validate([
            'cash_end' => 'required|numeric|min:0'
        ]);

        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())->whereNull('end_time')->first();
        if (!$activeShift) {
            return back()->with('error', 'Tidak ada shift aktif.');
        }

        $activeShift->update([
            'end_time' => now(),
            'cash_end' => $request->cash_end,
        ]);
        
        $cashTransactions = \App\Models\Transaksi::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('created_at', '>=', $activeShift->start_time)
                ->where('created_at', '<=', $activeShift->end_time)
                ->where('payment_method', 'cash')
                ->sum('payable_amount');
            
        $expectedCash = $activeShift->cash_start + $cashTransactions;
        $difference = $request->cash_end - $expectedCash;

        $msg = 'Shift berhasil diakhiri.';
        if ($difference < 0) {
            $msg .= ' Terdapat KEKURANGAN kas (Minus) sebesar Rp ' . number_format(abs($difference), 0, ',', '.');
        } elseif ($difference > 0) {
            $msg .= ' Terdapat KELEBIHAN kas (Surplus) sebesar Rp ' . number_format($difference, 0, ',', '.');
        } else {
            $msg .= ' Kas SEIMBANG (Sesuai perhitungan).';
        }

        $this->logActivity('mengakhiri shift dengan uang fisik akhir Rp ' . number_format($request->cash_end, 0, ',', '.'));

        return redirect()->route('kasir.shift')->with('success', $msg);
    }
}
