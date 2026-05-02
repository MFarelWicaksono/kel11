<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back_to_list')
                ->label('Kembali ke Riwayat') // Kita ganti judulnya biar pas
                ->url(static::getResource()::getUrl('index')) // Ini akan balik ke tabel Riwayat
                ->color('warning')
                ->icon('heroicon-m-arrow-left')
                ->extraAttributes([
                    'style' => 'background-color: #F39200 !important; color: #ffffff !important; font-weight: bold; border: none;'
                ]),
        ];
    }
}