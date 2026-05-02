<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KeuntunganChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Keuntungan (7 Hari Terakhir)';
    protected static ?int $sort = 1; // Biar muncul paling atas

    protected function getData(): array
    {
        // Ambil data 7 hari terakhir
        $data = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_name', '=', 'products.name')
            ->select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('SUM(transaction_items.subtotal - (transaction_items.qty * products.cost_price)) as profit')
            )
            ->where('transactions.created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Keuntungan (Rp)',
                    'data' => $data->pluck('profit')->toArray(),
                    'backgroundColor' => '#F39200', // Warna Orange Ibu
                    'borderColor' => '#004193',     // Garis Biru Navy
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Grafik garis biar kelihatan naik turunnya
    }
}
