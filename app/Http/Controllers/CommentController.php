<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'blog_id' => $blogId,
        ]);

        return redirect()->route('blogs.show', $blogId)->with('success', 'Comment posted successfully.');
    }
}
