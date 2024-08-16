<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function index(Request $request)
    {
        $blogs = Blog::with('user')->latest()->paginate(10);
        return inertia('Home', ['blogs' => $blogs]);
    }

    public function apiIndex(Request $request)
    {
        $blogs = Blog::with('user')->latest()->paginate(10);
        return response()->json($blogs);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Blog post created successfully!');
    }
}
