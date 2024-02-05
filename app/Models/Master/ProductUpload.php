<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUpload extends Model
{
    use HasFactory;

    protected $fillable = ['file_path', 'original_file_name', 'created_by_id', 'updated_by_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
