<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
  /**
   * The resource that this resource collects.
   *
   * @var string
   */
  public $collects = Post::class;

  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $isRequired = $this->required == 0 ? false : true;

    return [
      'type' => 'post',
      'id' => $this->id,
      'title' => $this->title,
      'body' => $this->body,
      'required' => $isRequired,
      'user' => new UserResource($this->user),
      'comments' => CommentResource::collection($this->comments),
      'attachments' => $this->attachment_link ? (str_starts_with($this->attachment_link, 'http') ? ([["path" => $this->attachment_link]]) : ([["path" => Storage::disk(config('voyager.storage.disk'))->url($this->attachment_link)]])) : null,
      'link' => $this->link,
      'likes' => $this->likes ?? '',
      'reactions' => $this->reactions ?? '',
      'isReacted' => $this->isReactedByAuthUser(),
      'isLiked' => $this->isLikedByAuthUser(),
      'created_at' => $this->created_at->toFormattedDateString(),
      'updated_at' => $this->updated_at->toFormattedDateString(),
    ];
  }
}
