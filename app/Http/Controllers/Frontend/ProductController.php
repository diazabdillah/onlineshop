<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Master\Product;
use App\Repositories\CrudRepositories;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;
    public function __construct()
    {
        $this->product = new CrudRepositories(new Product());
    }

    public function index()
    {
        //   // Ambil data produk beserta review-nya
        //   $product = Product::with('reviews.user')->findOrFail($productId);

        //   // Hitung jumlah user yang memberikan rating
        //   $totalUsers = $product->reviews->count();
  
        //   // Hitung total bintang (jumlah rating)
        //   $totalStars = $product->reviews->sum('rating');
        //   $averageRating = $totalStars / max($totalUsers, 1);
        $data['product'] = $this->product->Query()->with('reviews.user')->paginate(12);
    
    // Hitung rating untuk setiap produk
    $data['product']->each(function ($product) {
        $product->totalUsers = $product->reviews->count();
        $product->totalStars = $product->reviews->sum('rating');
        $product->averageRating = $product->totalStars / max($product->totalUsers, 1);
    });
        
        return view('frontend.product.index', compact('data'));
    }

    public function show($categoriSlug,$productSlug)
    {
           // ... existing code ...
           $data['product'] = $this->product->Query()->where('slug', $productSlug)->first();
        
           // Fetch reviews after getting the product
           $totalUsers = $data['product']->reviews->count();
           $totalStars = $data['product']->reviews->sum('rating');
           $averageRating = $totalStars / max($totalUsers, 1);
     
           $data['product_related'] = $this->product->Query()->whereNotIn('slug', [$productSlug])->limit(4)->get();
           return view('frontend.product.show', compact('data', 'totalUsers', 'totalStars', 'averageRating'));
    }

    public function search(Request $request)
    {
        // ... existing code ...
        $data['product'] = $this->product->Query()->with('reviews.user')->where('name', 'like', '%' . $request->q . '%')->paginate(12);
        
       // Hitung rating untuk setiap produk
    $data['product']->each(function ($product) {
        $product->totalUsers = $product->reviews->count();
        $product->totalStars = $product->reviews->sum('rating');
        $product->averageRating = $product->totalStars / max($product->totalUsers, 1);
    });
        
        
        return view('frontend.product.search', compact('data'));
    }
}
