<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'cities' => $this->whenLoaded('cities'),
            'imageUrl' => $this->getFirstMediaUrl('posters'),
            'thumbUrl' => $this->getFirstMediaUrl('posters', 'thumb'),
            'thumbAdminUrl' => $this->getFirstMediaUrl('posters', 'thumb_admin'),
        ];
    }
}
