<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimedVoucher extends Model
{
    use HasFactory;
    protected $table = 'claimed_vouchers';
    protected $fillable = ['user_id', 'voucher_id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
