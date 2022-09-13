<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{

  /**
   * @param Post $post
   * @return AnonymousResourceCollection
   */
  public function index(Post $post): AnonymousResourceCollection
  {
    return CommentResource::collection($post->comments()->with('user')->get());
  }

  /**
   * @param Request $request
   * @return CommentResource
   */
  public function store(Request $request): CommentResource
  {
    $comment = Comment::create([
      'body' => $request->body,
      'user_id' => $request->user()->id,
      'post_id' => $request->postId
    ]);

    return new CommentResource($comment);
  }

  /**
   * @param Request $request
   * @param Comment $comment
   * @return CommentResource
   */
  public function update(Request $request, Comment $comment): CommentResource
  {
    $comment = $comment->update($request->all());

    return new CommentResource($comment);
  }

  /**
   * @param Comment $comment
   * @return int
   * @throws \Exception
   */
  public function destroy(Comment $comment): int
  {
    return $comment->delete();
  }
}
