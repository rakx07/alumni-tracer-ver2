<?php

namespace App\Http\Controllers;

use App\Models\CareerPost;
use Illuminate\Http\Request;

class PublicCareerController extends Controller
{
    public function index(Request $request)
    {
        $posts = CareerPost::withCount('attachments')
            ->where('is_published', true)
            ->orderByDesc('id')
            ->paginate(12);

        return view('careers.index', compact('posts'));
    }

    public function show(CareerPost $post)
    {
        abort_unless($post->is_published, 404);

        $post->load('attachments');

        return view('careers.show', compact('post'));
    }
}
