<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_omzet = Transaksi::where('status', 'completed')->sum('payable_amount');
        $total_pengeluaran = Pengeluaran::sum('amount');
        $total_diskon = Transaksi::where('status', 'completed')->sum('discount_amount');
        $laba_bersih = $total_omzet - $total_pengeluaran;

        $transaksi_hari_ini = Transaksi::whereDate('created_at', Carbon::today())->count();
        $total_menu = Menu::count();
        $total_kasir = User::where('role', 'kasir')->count();
        
        // Best selling product - Sum quantity from completed transactions
        $best_seller = \App\Models\DetailTransaksi::select('menu_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('transaksi', function($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->first();
        
        $produk_terlaris = $best_seller && $best_seller->menu ? $best_seller->menu->name : '-';

        // Best Cashier this month
        $best_cashier_data = \App\Models\Transaksi::select('user_id', DB::raw('SUM(payable_amount) as total_omzet'), DB::raw('COUNT(id) as total_transaksi'))
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('user_id')
            ->orderByDesc('total_omzet')
            ->with('user')
            ->first();

        $kasir_terbaik = $best_cashier_data && $best_cashier_data->user ? $best_cashier_data->user->name : '-';
        $kasir_terbaik_omzet = $best_cashier_data ? $best_cashier_data->total_omzet : 0;

        // --- CHART DATA CALCULATIONS ---
        // (keeping chart logic unchanged)
        $startOfWeek = Carbon::now()->startOfWeek(); 
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weekly_sales = [];
        $weekly_trans = [];
        $weekly_labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weekly_sales[] = Transaksi::whereDate('created_at', $date)->where('status', 'completed')->sum('payable_amount');
            $weekly_trans[] = Transaksi::whereDate('created_at', $date)->where('status', 'completed')->count();
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthly_view_sales = [];
        $monthly_view_trans = [];
        $monthly_view_labels = [];
        $currentDate = $startOfMonth->copy();
        $weekCounter = 1;

        while ($currentDate <= $endOfMonth) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->endOfWeek();
            if ($weekEnd > $endOfMonth) $weekEnd = $endOfMonth->copy();
            $monthly_view_labels[] = "Minggu $weekCounter";
            $monthly_view_sales[] = Transaksi::whereBetween('created_at', [$weekStart, $weekEnd])->where('status', 'completed')->sum('payable_amount');
            $monthly_view_trans[] = Transaksi::whereBetween('created_at', [$weekStart, $weekEnd])->where('status', 'completed')->count();
            $currentDate->addWeek()->startOfWeek();
            $weekCounter++;
        }

        $year = Carbon::now()->year;
        $yearly_sales = [];
        $yearly_trans = [];
        $yearly_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 1; $i <= 12; $i++) {
            $yearly_sales[] = Transaksi::whereYear('created_at', $year)->whereMonth('created_at', $i)->where('status', 'completed')->sum('payable_amount');
            $yearly_trans[] = Transaksi::whereYear('created_at', $year)->whereMonth('created_at', $i)->where('status', 'completed')->count();
        }

        $chart_data = [
            'weekly' => [
                'labels' => $weekly_labels, 
                'sales' => $weekly_sales, 
                'trans' => $weekly_trans,
                'context' => $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M Y')
            ],
            'monthly' => [
                'labels' => $monthly_view_labels, 
                'sales' => $monthly_view_sales, 
                'trans' => $monthly_view_trans,
                'context' => Carbon::now()->translatedFormat('F Y')
            ],
            'yearly' => [
                'labels' => $yearly_labels, 
                'sales' => $yearly_sales, 
                'trans' => $yearly_trans,
                'context' => $year
            ]
        ];

        $pengeluarans = Pengeluaran::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'total_omzet', 'total_pengeluaran', 'laba_bersih', 'transaksi_hari_ini', 
            'total_kasir', 'produk_terlaris', 'chart_data', 'pengeluarans', 'total_diskon',
            'kasir_terbaik', 'kasir_terbaik_omzet'
        ));
    }
}
