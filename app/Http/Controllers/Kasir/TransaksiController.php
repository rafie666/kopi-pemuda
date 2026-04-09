<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['details.menu', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);
            
        return view('kasir.riwayat', compact('transaksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,mbanking',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        $activeShift = \App\Models\Shift::where('user_id', Auth::id())->whereNull('end_time')->first();
        if (!$activeShift) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki shift yang aktif. Silahkan mulai shift terlebih dahulu.'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $total_price = $request->total_price;
            $discount = $request->discount_amount ?: 0;
            $payable = $total_price - $discount;

            // Create Transaction
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'total_price' => $total_price,
                'discount_amount' => $discount,
                'payable_amount' => $payable,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            // Create Transaction Details and Deduct Stock
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['id']);
                
                if ($menu->stock < $item['quantity']) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi (Tersisa: {$menu->stock})");
                }

                $menu->decrement('stock', $item['quantity']);

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            $this->logActivity('memproses transaksi senilai Rp ' . number_format($payable, 0, ',', '.'));

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil diproses',
                'transaksi_id' => $transaksi->id,
                'snap_token' => null
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
