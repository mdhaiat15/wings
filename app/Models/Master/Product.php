<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function uploads()
    {
        return $this->hasOne(ProductUpload::class);
    }

    public function getUploadLabelAttribute()
    {
        if (!empty($this->uploads)) {
            return $this->uploads->pluck('file_path', 'id')->toArray();
        } else {
            return '';
        }
    }

    public function getDiscountLabelAttribute()
    {
        if (!empty($this->discount)) {
            return $this->discount . '%';
        } else {
            return '0';
        }
    }
}
