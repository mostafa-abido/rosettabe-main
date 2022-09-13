<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
  /**
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   */
  public function index()
  {
    return PostResource::collection(Post::with('user', 'comments')->where('status', '=', Post::PUBLISHED)->orderBy('updated_at', 'DESC')->cursorPaginate(15));
  }

  /**
   * @param StorePostRequest $request
   * @return PostResource
   */
  public function store(StorePostRequest $request): PostResource
  {
    $post = Post::create([
      'title' => $request->title,
      'body' => $request->body,
      'author_id' => $request->user()->id,
      'attachment_link' => sizeof($request->post('files')) > 0 ? $request->post('files')[0] : null,
      'link' => $request->link,
      'status' => 'pending',
    ]);

    return new PostResource($post);
  }

  /**
   * @param Post $post
   * @return PostResource
   */
  public function show(Post $post): PostResource
  {
    return new PostResource($post->load('comments.user', 'user'));
  }

  /**
   * @param UpdatePostRequest $request
   * @param Post $post
   * @return PostResource
   */
  public function update(UpdatePostRequest $request, Post $post): PostResource
  {
    $post->update($request->all());

    return new PostResource($post->load('comments.user', 'user'));
  }

  /**
   * @param Post $post
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function attachLike(Post $post)
  {
    if ($post->isLikedByAuthUser()) {
      $post->likes()->whereUserId(Auth::id())->delete();

      return response()->json([
        'count' => $post->likes()->count(),
        'isLiked' => $post->isLikedByAuthUser()
      ]);
    } else {
      $post->likes()->create([
        'user_id' => auth()->user()->id,
        'liked' => 1
      ]);

      return response()->json([
        'count' => $post->likes()->count(),
        'isLiked' => $post->isLikedByAuthUser()
      ]);
    }
  }

  /**
   * @param Post $post
   * @return bool|null
   * @throws \Exception
   */
  public function delete(Post $post): ?bool
  {
    $post->delete();

    return 'ok';
  }

  /**
   * @param Post $post
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function reactToPost(Post $post)
  {
    return $post->reactions()->updateOrCreate([
      'user_id' => Auth::id()
    ], [
      'reacted' => true
    ]);
  }
}
