<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanKeuntungan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.pages.laporan-keuntungan';
    protected static ?string $title = 'Laporan Keuntungan';

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function getLaporanDataProperty()
    {
        // Ambil data penjualan berdasarkan range tanggal
        return DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_name', '=', 'products.name') // Mencocokkan nama
            ->select(
                'transaction_items.product_name',
                DB::raw('SUM(transaction_items.qty) as total_terjual'),
                'products.cost_price as modal_satuan',
                DB::raw('SUM(transaction_items.qty * products.cost_price) as total_modal'),
                DB::raw('SUM(transaction_items.subtotal) as total_penjualan'),
                DB::raw('SUM(transaction_items.subtotal - (transaction_items.qty * products.cost_price)) as keuntungan')
            )
            ->whereBetween('transactions.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->groupBy('transaction_items.product_name', 'products.cost_price')
            ->get();
    }

    public function getRingkasanProperty()
    {
        $data = $this->laporan_data;
        return [
            'total_penjualan' => $data->sum('total_penjualan'),
            'total_modal' => $data->sum('total_modal'),
            'total_keuntungan' => $data->sum('keuntungan'),
        ];
    }
}
