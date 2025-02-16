<?php

namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Master\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ReviewController extends Controller
{
    public function showReviews($productId)
    {
        // Ambil data produk beserta review-nya
        $product = Product::with('reviews.user')->findOrFail($productId);

        // Hitung jumlah user yang memberikan rating
        $totalUsers = $product->reviews->count();

        // Hitung total bintang (jumlah rating)
        $totalStars = $product->reviews->sum('rating');
        $averageRating = $totalStars / max($totalUsers, 1);
        // Tampilkan view dengan data produk, review, totalUsers, dan totalStars
        return view('products.reviews', compact('product', 'totalUsers', 'totalStars', 'averageRating'));
    }
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'rating_pelayanan' => 'required|integer|min:1|max:5',
            'rating_pengiriman' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk gambar
            'video' => 'nullable|mimes:mp4,mov,avi|max:50240', // Validasi untuk video
        ]);

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('review_images', 'public');
        }

        // Simpan video jika ada
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('review_videos', 'public');
        }

        // Simpan review ke database
        $review = new Review();
        $review->product_id = $product->id;
        $review->user_id = Auth::id();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->rating_pelayanan = $request->rating_pelayanan;
        $review->rating_pengiriman = $request->rating_pengiriman;
        $review->image = $imagePath;
        $review->video = $videoPath;
        $review->save();

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
}
