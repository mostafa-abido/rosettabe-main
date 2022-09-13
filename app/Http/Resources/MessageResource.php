<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Traits\Resizable;

class MessageResource extends JsonResource
{
  use Resizable;
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'chat' => new ChatResource($this->chat),
      'message' => $this->message,
      'user' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'avatar' => $this->getThumbnail(Storage::disk(config('voyager.storage.disk'))->url($this->user->avatar), 'cropped-center'),
      ],
      'unreadBy' => $this->unreadMessages->map(function ($unreadMessage) {
        return new UnreadMessageResource($unreadMessage);
      }),
      'attachments' => $this->attachments,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at->toFormattedDateString(),
    ];
  }
}
