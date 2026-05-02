<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function items() {
    return $this->hasMany(TransactionItem::class);
}
protected $guarded = []; // Buka kunci
}
