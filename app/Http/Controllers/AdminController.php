<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPosts    = BlogPost::count();
        $publishedPosts = BlogPost::where('is_published', true)->count();
        $recentPosts   = BlogPost::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalPosts', 'publishedPosts', 'recentPosts'));
    }
}
