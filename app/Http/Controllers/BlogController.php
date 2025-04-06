<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Repost;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    // BlogController.php
    public function index()
    {
        $blogs = Blog::with(['user', 'comments.user', 'reposts.user', 'originalAuthor']) // Eager load originalAuthor
            ->latest()
            ->paginate(1); // Adjust pagination as needed

        return Inertia::render('Blog/Index', [
            'blogs' => $blogs,
            'auth' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    public function repost($id)
    {
        $blog = Blog::findOrFail($id);

        // Check if the user has already reposted this blog
        if (
            Repost::where('blog_id', $blog->id)
                ->where('user_id', auth()->id())
                ->exists()
        ) {
            return redirect()->back()->with('error', 'You have already reposted this blog.');
        }

        // Create a repost record
        Repost::create([
            'blog_id' => $blog->id,
            'user_id' => auth()->id(),
        ]);

        // Fetch the updated blogs with reposts and their authors
        $blogs = Blog::with(['user', 'comments.user', 'reposts.user'])
            ->latest()
            ->paginate(10); // Adjust pagination as needed

        // Redirect back with success message and updated blogs
        return redirect()
            ->route('home')
            ->with([
                'success' => 'Blog reposted successfully!',
                'blogs' => $blogs, // Pass updated blogs
            ]);
    }

    public function deleteRepost($id)
    {
        $repost = Repost::where('blog_id', $id)->where('user_id', auth()->id())->firstOrFail();

        $repost->delete();

        return redirect()->route('home')->with('success', 'Repost deleted successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home')->with('success', 'Blog posted successfully!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return inertia('Blog/Edit', [
            'blog' => $blog,
        ]);
    }

    public function update(Request $request, $id)
{
    // Find the blog post by ID
    $blog = Blog::findOrFail($id);

    // Validate the request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:4294967295',
    ]);

    // Update the blog post
    $blog->update($validatedData);

    // Redirect to the updated blog post with a success message
    return redirect()->route('blogs.show', ['id' => $id])->with('success', 'Blog post updated successfully.');
}

    public function show($id)
    {
        $blog = Blog::with([
            'user',
            'comments' => function($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'comments.user'
        ])->findOrFail($id);

        $totalComments = Comment::where('blog_id', $id)->count();

        return Inertia::render('BlogShow', [
            'blog' => $blog,
            'totalComments' => $totalComments,
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        // Check if the logged-in user is the author
        if (auth()->user()->id !== $blog->user_id) {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this post.');
        }

        $blog->delete();

        return redirect()->route('home')->with('success', 'Blog post deleted successfully.');
    }
}
