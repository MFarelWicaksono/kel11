<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class Kasir extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.kasir';
    protected static ?string $title = 'Kasir Toko';

    public $cart = [];
    public $amount_paid = 0;
    public $amount_paid_formatted = "";

    public function addToCart($productId)
    {
        if (!$productId) return;

        $product = Product::find($productId);
        if (!$product) return;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => $product->selling_price,
                'qty' => 1,
            ];
        }
    }

    public function incrementQty($productId)
    {
        // PERBAIKAN: Cek apakah key ada di array
        if (isset($this->cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
            } else {
                Notification::make()->title('Stok terbatas!')->warning()->send();
            }
        }
    }

    public function decrementQty($productId)
    {
        // PERBAIKAN: Cek apakah key ada di array
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
            } else {
                unset($this->cart[$productId]);
            }
        }
    }

    public function updatedAmountPaidFormatted($value)
    {
        $this->amount_paid = (int) str_replace('.', '', $value);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function getChangeProperty()
    {
        return max(0, $this->amount_paid - $this->total);
    }

    public function saveTransaction()
    {
        if (empty($this->cart)) {
            Notification::make()->title('Keranjang kosong!')->danger()->send();
            return;
        }

        if ($this->amount_paid < $this->total) {
            Notification::make()->title('Uang bayar kurang!')->danger()->send();
            return;
        }

        try {
            DB::transaction(function () {
                $transaction = Transaction::create([
                    'total_price' => $this->total,
                    'amount_paid' => $this->amount_paid,
                    'change' => $this->change,
                ]);

                foreach ($this->cart as $id => $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ]);

                    Product::find($id)->decrement('stock', $item['qty']);
                }
            });

            Notification::make()->title('Transaksi Berhasil!')->success()->send();
            $this->cart = [];
            $this->amount_paid = 0;
            $this->amount_paid_formatted = "";
        } catch (\Exception $e) {
            Notification::make()->title('Gagal: ' . $e->getMessage())->danger()->send();
        }
    }
}
