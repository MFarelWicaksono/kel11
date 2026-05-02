<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    // --- PENGATURAN BAHASA INDONESIA (POSISI SUDAH BENAR) ---
    protected static ?string $navigationLabel = 'Stok Barang';
    protected static ?string $breadcrumb = 'Stok Barang';
    protected static ?string $modelLabel = 'Produk'; 
    protected static ?string $pluralModelLabel = 'Stok Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang & Stok')
                    ->description('Input harga beli per dus, sistem akan menghitung modal per biji.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Barang')
                            ->required()
                            ->placeholder('Contoh: Mie Goreng Spesial')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('box_content')
                            ->label('Isi per Dus/Ball')
                            ->numeric()
                            ->default(1)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function ($get, $set) {
                                $boxPrice = (int) str_replace('.', '', $get('cost_price_box') ?? 0);
                                $content = (int) $get('box_content');
                                if ($content > 0) {
                                    $set('cost_price', $boxPrice / $content);
                                }
                            }),

                        Forms\Components\TextInput::make('cost_price_box')
                            ->label('Harga Beli Per Dus')
                            ->prefix('Rp')
                            ->extraInputAttributes(['onkeyup' => "this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"])
                            ->live(onBlur: true)
                            ->dehydrated(false)
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $cleanValue = (int) str_replace('.', '', $state);
                                if ($cleanValue > 0 && $cleanValue < 1000) {
                                    $cleanValue *= 1000;
                                    $set('cost_price_box', number_format($cleanValue, 0, ',', '.'));
                                }
                                $content = (int) $get('box_content');
                                if ($content > 0) {
                                    $set('cost_price', $cleanValue / $content);
                                }
                            }),

                        Forms\Components\TextInput::make('cost_price')
                            ->label('Harga Modal Satuan (Otomatis)')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->helperText('Hasil hitung otomatis modal per biji.'),

                        Forms\Components\TextInput::make('selling_price')
                            ->label('Harga Jual per Biji')
                            ->prefix('Rp')
                            ->required()
                            ->extraInputAttributes(['onkeyup' => "this.value = this.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"])
                            ->dehydrateStateUsing(fn ($state) => (int) str_replace('.', '', $state))
                            ->afterStateHydrated(fn ($state, $set) => $set('selling_price', number_format((int)$state, 0, ',', '.'))),

                        Forms\Components\TextInput::make('stock')
                            ->label('Total Stok (Biji)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])->columns(['sm' => 1, 'md' => 2])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => $state <= 5 ? 'danger' : 'success')
                    ->suffix(' Pcs'),
                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Modal')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}