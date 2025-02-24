<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createadmin()
    {
        return view('backend.posts');
    }

    public function storeadmin(Request $request)
    {
        $request->validate([
            'caption' => 'required|string|max:150',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'schedule' => 'required',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Post::create([
            'caption' => $request->caption,
            'thumbnail' => $thumbnailPath,
            'schedule' => $request->schedule,
            'duet' => $request->has('duet'),
            'stitch' => $request->has('stitch'),
        ]);

        return redirect()->route('posts.create')->with('success', 'Postingan berhasil dibuat!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'video_url' => 'required|url',
        ]);

        $post = Post::create($request->all());

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with(['comments', 'likes'])->findOrFail($id);
        return response()->json($post);
    }
}
