<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Riwayat Penjualan';
    protected static ?string $pluralModelLabel = 'Riwayat Penjualan';

    // Bagian untuk mengatur tampilan DETAIL saat diklik tombol View
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')->label('Waktu Transaksi')->dateTime(),
                        Infolists\Components\TextEntry::make('total_price')->label('Total Belanja')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                        Infolists\Components\TextEntry::make('amount_paid')->label('Uang Tunai')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                        Infolists\Components\TextEntry::make('change')->label('Kembalian')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                    ])->columns(['sm' => 1, 'md' => 2]),

                Infolists\Components\Section::make('Daftar Barang yang Dibeli')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items') // Pastikan ada relasi 'items' di Model Transaction
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')->label('Nama Barang'),
                                Infolists\Components\TextEntry::make('price')->label('Harga Satuan')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                                Infolists\Components\TextEntry::make('qty')->label('Jumlah'),
                                Infolists\Components\TextEntry::make('subtotal')->label('Subtotal')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            ])->columns(['sm' => 1, 'md' => 4])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal & Jam')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Belanja')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'), // Biru Navy
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Bayar')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                Tables\Columns\TextColumn::make('change')
                    ->label('Kembalian')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->color('warning'), // Orange
            ])
            ->defaultSort('created_at', 'desc') // Yang terbaru muncul paling atas
            ->actions([
                Tables\Actions\ViewAction::make(), // Tombol untuk lihat detail barang
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    // Cari bagian ini di paling bawah file TransactionResource.php
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
    // Tambahkan ini di dalam class TransactionResource
    public static function canCreate(): bool
    {
        return false; // Ini akan menghilangkan tombol "New" secara total
    }
}
