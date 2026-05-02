<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPendapatan = Transaction::sum('total_price');
        
        // Menghitung keuntungan kotor
        $totalKeuntungan = TransactionItem::join('products', 'transaction_items.product_name', '=', 'products.name')
            ->selectRaw('SUM(transaction_items.qty * (transaction_items.price - products.cost_price)) as total_profit')
            ->value('total_profit') ?? 0;

        $totalProduk = Product::count();

        // Mengambil tren 7 hari terakhir untuk sparkline
        $trendPendapatan = [];
        $trendKeuntungan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            
            $harianPendapatan = Transaction::whereDate('created_at', $date)->sum('total_price');
            $trendPendapatan[] = $harianPendapatan;

            $harianKeuntungan = TransactionItem::join('products', 'transaction_items.product_name', '=', 'products.name')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereDate('transactions.created_at', $date)
                ->selectRaw('SUM(transaction_items.qty * (transaction_items.price - products.cost_price)) as profit')
                ->value('profit') ?? 0;
            $trendKeuntungan[] = $harianKeuntungan;
        }

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Total uang yang masuk dari kasir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($trendPendapatan)
                ->color('success'),
                
            Stat::make('Total Keuntungan', 'Rp ' . number_format($totalKeuntungan, 0, ',', '.'))
                ->description('Margin keuntungan stabil')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart($trendKeuntungan)
                ->color('primary'),
                
            Stat::make('Jumlah Jenis Produk', $totalProduk)
                ->description('Barang aktif di etalase')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }
}
