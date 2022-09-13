<?php

namespace App\Http\Controllers\Api;

use App\Helpers\General\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PollResource;
use App\Http\Resources\PostResource;
use App\Models\Poll;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function feed(Request $request)
    {
        $posts = PostResource::collection(Post::published()->latest()->load('user')->paginate(5));
        $polls = PollResource::collection(Poll::latest()->with('options')->paginate(5));

        $content =  $posts->merge($polls);

        return $content->paginate(10);
    }
}
