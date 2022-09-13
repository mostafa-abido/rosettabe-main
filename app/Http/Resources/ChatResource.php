<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
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
      'name' => $this->name,
      'team_only' => !!$this->auto_add_teammembers,
      'members' => UserResource::collection($this->members),
      'notes' => NoteResource::collection(Note::latest()->where('chat_id', $this->id)->paginate(20)),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
