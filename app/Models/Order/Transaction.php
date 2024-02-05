<?php

namespace App\Models\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'doc_number'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDocumentLabelAttribute()
    {
        if (!empty($this->doc_number)) {
            return $this->doc_code . '-' . $this->doc_number;
        } else {
            return '';
        }
    }

    public function getUserLabelAttribute()
    {
        if (!empty($this->users)) {
            return $this->users->name;
        } else {
            return '';
        }
    }

    public function getTotalLabelAttribute()
    {
        if (!empty($this->total)) {
            return number_format($this->total, 0, ',', '.');
        } else {
            return '';
        }
    }
}
