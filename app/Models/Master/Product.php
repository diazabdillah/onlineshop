<?php

namespace App\Models\Master;

use App\Models\Feature\Order;
use App\Models\Feature\OrderDetail;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products'; 
    protected $guarded = [];
    // protected $fillable = [
    //     'name', 'price', 'discounted_price', 'discount_percentage', 'thumbnails', 'category_id', 'slug'
    // ];
    protected $appends = ['thumbnails_path','price_rupiah','total_sold'];

    public function Category()
    {
        return $this->belongsTo(Category::class,'categories_id');
    }

    public function OrderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function getDiscountPercentageAttribute()
    {
        if ($this->price > $this->discounted_price) { // Memastikan harga asli lebih besar dari 0
            return $this->discounted_price ? round((($this->price - $this->discounted_price) / $this->price) * 100, 2) : 0;
        }
        return 0;
    }

    public function getThumbnailsPathAttribute()
    {
        return asset('storage/' . $this->thumbnails);
    }

    public function getPriceRupiahAttribute()
    {
        return "Rp " . number_format($this->price,0,',','.');
    }

    public function getTotalSoldAttribute()
    {
        return $this->OrderDetails()->whereHas('Order',function($q){
            $q->whereIn('status',[2,3]);
        })->sum('qty');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
