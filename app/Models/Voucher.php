<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
    ];

    protected $dates = [
        'valid_from',
        'valid_until',
    ];

    public function isValid()
    {
        if ($this === null) {
            return false; // Atau bisa juga throw exception sesuai kebutuhan
        }
        $now = now();
        return $this->valid_from <= $now && $this->valid_until >= $now && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
