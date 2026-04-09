<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class OrderController extends Controller
{
    public function index()
    {
        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())->whereNull('end_time')->first();
        if (!$activeShift) {
            return redirect()->route('kasir.shift')->with('warning', 'Silahkan mulai shift terlebih dahulu sebelum melakukan transaksi.');
        }

        $menus = Menu::all();
        $topMenuIds = \App\Models\DetailTransaksi::select('menu_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->pluck('menu_id')
            ->toArray();

        return view('kasir.order', compact('menus', 'topMenuIds'));
    }
}
