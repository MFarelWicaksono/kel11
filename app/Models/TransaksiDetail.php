namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
protected $guarded = [];

public function transaksi()
{
return $this->belongsTo(Transaksi::class);
}

public function produk()
{
return $this->belongsTo(Produk::class);
}
}