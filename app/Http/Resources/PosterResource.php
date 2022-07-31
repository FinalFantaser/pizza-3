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
            'imageUrl450' => $this->getFirstMediaUrl('posters', 'size450'),
        ];
    }
}
