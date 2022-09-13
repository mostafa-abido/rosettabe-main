<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Voyager;
use TCG\Voyager\Traits\Resizable;

class UserResource extends JsonResource
{
  use Resizable;
  /**
   * The resource that this resource collects.
   *
   * @var string
   */
  public $collects = User::class;
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
      'avatar' => $this->avatar ? $this->getThumbnail(Storage::disk(config('voyager.storage.disk'))->url($this->avatar), 'cropped-center') : null,
      'alt_image' => $this->alt_image ? $this->getThumbnail(Storage::disk(config('voyager.storage.disk'))->url($this->alt_image), 'cropped-center') : null,
      'name' => $this->name,
      'surname' => $this->surname,
      'email' => $this->email,
      'phone' => $this->phone,
      'position' => $this->position,
      'location' => $this->location,
      'unreads' => $this->unreadMessages->map(function ($message) {
        return new UnreadMessageResource($message);
      }),
      'notifications' => $this->unreadNotifications->map(function ($notification) {
        return $notification->data;
      }),
      'is' => $this->role_id,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
