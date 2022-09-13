<?php

namespace App\Http\Resources;

use App\Models\Guest;
use App\Models\Home;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Traits\Resizable;

class GuestResource extends JsonResource
{
    use Resizable;
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = Guest::class;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
      return [
        'id' => $this->id,
        'fname' => $this->fname,
        'lname' => $this->lname,
        'alias' => $this->alias,
        'photo1' => $this->photo1 ? $this->getThumbnail(Storage::disk(config('voyager.storage.disk'))->url($this->photo1), 'cropped-center') : null,
        'photo2' => $this->photo2 ? $this->getThumbnail(Storage::disk(config('voyager.storage.disk'))->url($this->photo2), 'cropped-center') : null,
        'photo_release' => $this->photo_release,
        'photo_notes' => $this->photo_notes,
        'dob' => $this->dob,
        'type' => $this->type,
        'home' => $this->home_id,
        'dnr' => $this->dnr,
        'status' => $this->status,
        'life_story' => $this->life_story,
        'intellectual' => $this->intellectual,
        'social' => $this->social,
        'physical' => $this->physical,
        'spiritual' => $this->spiritual,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ];
    }
}
