<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'sort' => $this->sort,
            'slug' => $this->slug,
            'SEO_title' => $this->meta['title'] ?? null,
            'SEO_keywords' => $this->meta['keywords'] ?? null,
            'SEO_description' => $this->meta['description'] ?? null,
            'imageUrl' => $this->getFirstMediaUrl('categories'),
            'thumbUrl' => $this->imageUrl('thumb'),
            'sort' => $this->sort
        ];
    }
}
