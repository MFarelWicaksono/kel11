<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk membuka "pintu" database
    // Ini artinya: "Saya izinkan semua kolom diisi secara massal"
    protected $guarded = []; // Buka kunci
}
