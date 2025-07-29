<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class AdminController extends Controller
{
    public function index()
    {
        $posts = Post::all(); // fetch all posts for the admin index
        return view('admin.index', compact('posts'));
    }
}
