<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
  // Field yang dapat diisi (fillable)
  protected $fillable = [
    'product_id',
    'user_id',
    'review',
    'rating',
    'image', // Field untuk menyimpan path gambar
    'video', // Field untuk menyimpan path video
];

// Relasi ke model Product
public function product()
{
    return $this->belongsTo(Product::class);
}

// Relasi ke model User
public function user()
{
    return $this->belongsTo(User::class);
}

// Accessor untuk URL gambar
public function getImageUrlAttribute()
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }
    return null;
}

// Accessor untuk URL video
public function getVideoUrlAttribute()
{
    if ($this->video) {
        return asset('storage/' . $this->video);
    }
    return null;
}
}
