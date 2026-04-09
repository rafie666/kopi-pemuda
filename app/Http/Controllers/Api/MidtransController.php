<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Order ID format: TRX-{id}-{timestamp}
        $orderParts = explode('-', $request->order_id);
        $transaksiId = $orderParts[1];
        $transaksi = Transaksi::findOrFail($transaksiId);

        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $transaksi->update(['status' => 'completed']);
            } else if ($transactionStatus == 'pending') {
                $transaksi->update(['status' => 'pending']);
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                if ($transaksi->status !== 'cancelled') {
                    // Re-add stock
                    foreach ($transaksi->details as $detail) {
                        $menu = Menu::find($detail->menu_id);
                        if ($menu) {
                            $menu->increment('stock', $detail->quantity);
                        }
                    }
                    $transaksi->update(['status' => 'cancelled']);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
