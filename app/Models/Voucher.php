<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'type',
        'min_purchase',
        'max_discount', // ... existing code ...
    ];

    protected $dates = [
        'valid_from',
        'valid_until',
    ];
    public function claimedVouchers()
    {
        return $this->hasMany(ClaimedVoucher::class, 'voucher_id'); // Menambahkan parameter untuk kunci asing
    }
    public function isValid()
    {
        if ($this === null) {
            return false; // Atau bisa juga throw exception sesuai kebutuhan
        }
        $now = now();
        return $this->valid_from <= $now && $this->valid_until >= $now && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
  
    public function calculateDiscount($total) {
        if ($this->type == 'percentage') {
            $calculatedDiscount = ($total * $this->discount / 100);
            if ($this->max_discount !== null) {
                $calculatedDiscount = min($calculatedDiscount, $this->max_discount); // Pastikan tidak melebihi max_discount
            }
            return min($calculatedDiscount, $total); // Pastikan tidak melebihi total
        } else {
            return min($this->discount, $total); // Jika fixed, pastikan tidak melebihi total
        }
    }
}
