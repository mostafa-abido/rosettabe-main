<?php

namespace App\Http\Resources;

use App\Models\ResourceAttachment;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceAttachmentResource extends JsonResource
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ResourceAttachment::class;


    public function getFilePath()
    {
        if ($this->type === 'IMAGE'){
            return "/storage/".json_decode($this->file)[0]->download_link;
        } else {
            return '/storage/'.json_decode($this->file)[0]->download_link;
        }
    }

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
            'description' => $this->description,
            'type' => $this->type,
            'file' => $this->getFilePath(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
