<?php

namespace App\Http\Resources;

use App\Models\MessageReads;
use Illuminate\Http\Resources\Json\JsonResource;

class UnreadMessageResource extends JsonResource
{

  public $collects = MessageReads::class;
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return $this->only([
      'id', 'user_id', 'chat_id'
    ]);
  }
}
