<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'comment' => $request->comment,
        ]);

        return response()->json($comment, 201);
    }
}
