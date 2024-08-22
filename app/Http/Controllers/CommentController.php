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

    public function destroy(Comment $comment)
{
    // Check if the authenticated user is the owner of the comment
    if ($comment->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
    }

    $comment->delete();

    return redirect()->back()->with('success', 'Comment deleted successfully!');
}

}
