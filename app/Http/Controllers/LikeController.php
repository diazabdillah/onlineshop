<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Master\Product;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likeProduct($name)
    {
        $user = Auth::user();
        $product = Product::where('name', $name)->firstOrFail(); // Find product by name

        // Cek apakah user sudah like
        $like = Like::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create(['user_id' => $user->id, 'product_id' => $product->id]);
        }

        // Ambil jumlah like terbaru
        $likeCount = Like::where('product_id', $product->id)->count();

        return response()->json([
            'message' => $like ? 'Unlike berhasil' : 'Like berhasil',
            'like_count' => $likeCount
        ]);
    }
    public function getLikes($name)
{
    $product = Product::where('name', $name)->firstOrFail();
    $likeCount = $product->likes()->count();

    return response()->json(['like_count' => $likeCount]);
}
}
