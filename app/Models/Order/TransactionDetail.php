<?php

namespace App\Models\Order;

use App\Models\Master\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductLabelAttribute()
    {
        if (!empty($this->product)) {
            return $this->product->name;
        } else {
            return '';
        }
    }
}
