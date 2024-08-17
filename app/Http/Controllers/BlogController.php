<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{

    public function index()
    {
        $blogs = Blog::with('user')->paginate(10); // Eager loading the user who created the blog
        return Inertia::render('Blog/Index', [
            'blogs' => $blogs,
        ]);
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
