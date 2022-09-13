<?php

namespace App\Http\Resources;

use App\Models\Guest;
use App\Models\Home;
use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Traits\Resizable;

class HomeResource extends JsonResource
{
  use Resizable;
  /**
   * The resource that this resource collects.
   *
   * @var string
   */
  public $collects = Home::class;
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
        'alias' => $this->alias,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
      ];
    }
}
