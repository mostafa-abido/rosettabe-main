<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
{
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
      'data' => $this->unreadNotifications->map(function ($notification) {
        return $notification->only(['id', 'data']);
      })
    ];
  }
}
