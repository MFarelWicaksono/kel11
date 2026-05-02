<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BarangTerlarisChart extends ChartWidget
{
    protected static ?string $heading = 'Barang Paling Laris';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = DB::table('transaction_items')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $data->pluck('total_qty')->toArray(),
                    'backgroundColor' => [
                        '#004193', // Biru Navy
                        '#F39200', // Orange
                        '#004193',
                        '#F39200',
                        '#004193',
                    ],
                ],
            ],
            'labels' => $data->pluck('product_name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false, // Menghilangkan kotak warna di bawah
                ],
            ],
            'scales' => [
                'y' => [ // Pengaturan garis tegak (Atas-Bawah)
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                    'title' => [ // Penjelasan untuk garis tegak
                        'display' => true,
                        'text' => 'Total Produk (Pcs)',
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'x' => [ // Pengaturan garis datar (Kiri-Kanan)
                    'title' => [ // Penjelasan untuk garis bawah
                        'display' => true,
                        'text' => 'Nama Barang',
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
