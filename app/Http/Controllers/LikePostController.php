<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikePostController extends Controller
{
    public function store(Request $request, $postId)
    {
        $user = auth()->user(); // Ambil user yang login

        // Cek apakah user sudah like sebelumnya
        $like = Like::where('post_id', $postId)->where('user_id', $user->id)->first();

        if ($like) {
            return response()->json(['message' => 'You already liked this post.'], 400);
        }

        $like = Like::create([
            'post_id' => $postId,
            'user_id' => $user->id,
        ]);

        return response()->json($like, 201);
    }

}
