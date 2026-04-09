@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div x-data="{ 
    modalPengeluaran: false
}">

<!-- LIBRARY -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<!-- ================= DASHBOARD SUMMARY ================= -->
<div class="space-y-10 mb-10">

    <!-- KATEGORI: KEUANGAN & TRANSAKSI -->
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-4 bg-green-500 rounded-full"></span>
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest">Ringkasan Keuangan & Transaksi</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Laba Bersih -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 flex items-center justify-between shadow-lg text-white">
                <div>
                    <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider opacity-80 mb-2">Laba Bersih</p>
                    <h3 class="text-xl md:text-3xl font-black">Rp {{ number_format($laba_bersih,0,',','.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm"><i class="fa-solid fa-chart-line text-2xl md:text-3xl text-white"></i></div>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="bg-white rounded-2xl p-6 flex items-center justify-between shadow-sm border border-gray-100 group hover:border-red-500 transition-all duration-300">
                <div>
                    <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Transaksi Hari Ini</p>
                    <h3 class="text-xl md:text-3xl font-black text-gray-800 group-hover:text-red-600 transition-colors">{{ $transaksi_hari_ini }} Transaksi</h3>
                </div>
                <div class="bg-gray-50 group-hover:bg-red-50 p-4 rounded-xl transition-colors"><i class="fa-solid fa-cart-shopping text-2xl md:text-3xl text-gray-300 group-hover:text-red-500"></i></div>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white rounded-2xl p-6 flex items-center justify-between shadow-sm border border-gray-100 group hover:border-gray-800 transition-all duration-300">
                <div class="flex-1 overflow-hidden">
                    <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Produk Terlaris</p>
                    <h3 class="text-xl md:text-2xl font-black text-gray-800 truncate group-hover:text-gray-900 transition-colors">{{ $produk_terlaris ?: '-' }}</h3>
                </div>
                <div class="bg-gray-50 group-hover:bg-gray-100 p-4 rounded-xl transition-colors"><i class="fa-solid fa-fire text-2xl md:text-3xl text-gray-300 group-hover:text-orange-500"></i></div>
            </div>
        </div>
    </div>

    <!-- KATEGORI: SUMBER DAYA & PERFORMA -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pegawai Section -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 bg-blue-500 rounded-full"></span>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest">Manajemen Pegawai</h2>
            </div>
            <div class="bg-white rounded-2xl p-6 flex items-center justify-between shadow-sm border border-gray-100 h-[120px]">
                <div>
                    <p class="text-[10px] md:text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Total Pegawai Saat Ini</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $total_kasir }} <span class="text-sm font-medium text-gray-400">Personel</span></h3>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl"><i class="fa-solid fa-users text-3xl text-blue-500"></i></div>
            </div>
        </div>

        <!-- Reward Section -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 bg-yellow-500 rounded-full"></span>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest">Reward & Apresiasi</h2>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl p-6 flex items-center justify-between shadow-lg text-white relative overflow-hidden group h-[120px]">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-crown text-8xl"></i>
                </div>
                <div class="relative z-10 w-full flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-medal text-yellow-200"></i>
                            <p class="text-[10px] font-bold tracking-wider uppercase text-yellow-100">Kasir Terbaik Bulan Ini</p>
                        </div>
                        <h3 class="text-2xl font-black truncate drop-shadow-md">{{ $kasir_terbaik }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-yellow-200 uppercase font-black">Omzet</p>
                        <p class="text-lg font-bold">Rp {{ number_format($kasir_terbaik_omzet, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ================= GRAFIK ================= -->
<div class="bg-white rounded-lg shadow-sm p-4 md:p-8 mb-8 border border-gray-100">

    <div class="flex justify-between items-start mb-2">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Grafik Penjualan</h3>
            <p id="chartContext" class="text-sm text-gray-400 font-medium mt-1">Pendapatan - {{ $chart_data['weekly']['context'] }}</p>
        </div>

        <select id="periodSelector"
            class="border border-gray-200 rounded-xl text-sm px-4 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm transition-all h-[42px]">
            <option value="weekly">Mingguan</option>
            <option value="monthly">Bulanan</option>
            <option value="yearly">Tahunan</option>
        </select>
    </div>

    <!-- Custom Chart Legend -->
    <div class="flex justify-center items-center gap-6 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-10 h-3 bg-blue-500 rounded-sm border border-blue-600"></div>
            <span class="text-xs font-medium text-gray-500">Pendapatan</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-10 h-3 bg-yellow-400 rounded-sm border border-yellow-500"></div>
            <span class="text-xs font-medium text-gray-500">Transaksi</span>
        </div>
    </div>

    <div class="relative h-[300px] md:h-[450px] w-full mb-4">
        <canvas id="salesChart" class="!w-full !h-full"></canvas>
    </div>

    <div class="flex justify-end gap-2 pr-2">
        <button onclick="downloadExcel()"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-2">
            <span class="bg-white text-green-600 px-1 rounded-[4px] text-[8px] font-black">XLS</span> Excel
        </button>
        <button onclick="downloadPDF()"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md flex items-center gap-2">
            <span class="bg-white text-red-600 px-1 rounded-[4px] text-[8px] font-black">PDF</span> PDF
        </button>
    </div>

</div>

<!-- ================= TABEL PENGELUARAN (Recent) ================= -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">Pengeluaran Terbaru</h3>
        <button @click="modalPengeluaran = true" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
            <i class="fas fa-plus-circle text-xs"></i> Tambah Pengeluaran
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-y border-gray-100 uppercase tracking-wider text-[10px]">
                    <th class="py-4 px-6 font-bold text-gray-400">Tanggal</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Deskripsi</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Kategori</th>
                    <th class="py-4 px-6 font-bold text-gray-400">Jumlah</th>
                    <th class="py-4 px-6 font-bold text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengeluarans as $p)
                <tr class="hover:bg-gray-50 transition-colors text-sm">
                    <td class="py-4 px-6 text-gray-500">{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
                    <td class="py-4 px-6 font-medium text-gray-800">{{ $p->description }}</td>
                    <td class="py-4 px-6 text-gray-400 italic">{{ $p->category }}</td>
                    <td class="py-4 px-6 font-bold text-red-600">-Rp {{ number_format($p->amount,0,',','.') }}</td>
                    <td class="py-4 px-6 text-center">
                        <form action="{{ route('admin.pengeluarans.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition" onclick="return confirm('Hapus pengeluaran ini?')">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-400">Belum ada data pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



<!-- Modal Tambah Pengeluaran -->
<div x-show="modalPengeluaran" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-white/30 backdrop-blur-md" @click="modalPengeluaran = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="px-10 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-gray-800">Catat Pengeluaran</h3>
                    <button @click="modalPengeluaran = false" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.pengeluarans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Deskripsi / Kebutuhan</label>
                        <input type="text" name="description" required placeholder="Misal: Belanja Kopi, Bayar Listrik" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="block text-[15px] font-semibold text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                                <option value="Belanja Bahan">Belanja Bahan</option>
                                <option value="Gaji Pegawai">Gaji Pegawai</option>
                                <option value="Listrik & Air">Listrik & Air</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-[15px] font-semibold text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="block text-[15px] font-semibold text-gray-700 mb-2">Jumlah (Rp)</label>
                        <input type="number" name="amount" required class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all bg-white shadow-sm">
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl hover:bg-black transition-all shadow-lg text-lg">Simpan Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- ================= SCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allData = @json($chart_data);
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Gradient for area chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allData.weekly.labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: allData.weekly.sales,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Transaksi',
                    data: allData.weekly.trans,
                    type: 'bar',
                    backgroundColor: '#facc15',
                    borderRadius: 4,
                    barThickness: 30,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false // We use custom HTML legend
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1f2937',
                    bodyColor: '#1f2937',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.datasetIndex === 0) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            } else {
                                label += context.parsed.y + ' Transaksi';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11, weight: '500' },
                        color: '#9ca3af'
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: {
                        color: '#f3f4f6'
                    },
                    ticks: {
                        font: { size: 10 },
                        color: '#9ca3af',
                        callback: v => v >= 1000 ? 'Rp ' + (v/1000) + 'k' : 'Rp ' + v
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        font: { size: 10 },
                        color: '#9ca3af',
                        stepSize: 5
                    }
                }
            }
        }
    });

    document.getElementById('periodSelector').addEventListener('change', e => {
        const d = allData[e.target.value];
        salesChart.data.labels = d.labels;
        salesChart.data.datasets[0].data = d.sales;
        salesChart.data.datasets[1].data = d.trans;
        document.getElementById('chartContext').innerText = `Pendapatan - ${d.context}`;
        balanceYAxes(salesChart, d.sales, d.trans);
        salesChart.update();
    });

    // Initial axis balancing
    balanceYAxes(salesChart, allData.weekly.sales, allData.weekly.trans);
    salesChart.update();

    function balanceYAxes(chart, salesData, transData) {
        const maxSales = Math.max(...salesData, 100000);
        const maxTrans = Math.max(...transData, 10);
        // Align ticks roughly
        chart.options.scales.y.max = Math.ceil(maxSales / 100000) * 100000;
        chart.options.scales.y1.max = Math.ceil(maxTrans / 5) * 5;
    }
});

function downloadExcel() {
    const selector = document.getElementById('periodSelector');
    const allData = @json($chart_data);
    const p = selector.value;
    let csv = "Periode,Pendapatan,Transaksi\n";
    allData[p].labels.forEach((l,i)=>{
        csv += `${l},${allData[p].sales[i]},${allData[p].trans[i]}\n`;
    });
    const a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    a.download = `laporan_${p}.csv`;
    a.click();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Laporan Penjualan", 14, 20);
    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas) {
        doc.addImage(chartCanvas.toDataURL(), 'PNG', 14, 30, 180, 90);
    }
    doc.save("laporan_penjualan.pdf");
}

// Session Notifications
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
    });
@endif


</script>
</div> <!-- Penutup x-data (Line 7) -->
@endsection
