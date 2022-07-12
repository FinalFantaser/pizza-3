<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'status' => $this->status,
            'price' => $this->price,
            'price_sale' => $this->price_sale,
            'description' => $this->description,
            'imageUrl' => $this->imageUrl('product'),
            'thumbUrl' => $this->imageUrl('product_thumb_admin'),
            'category' => ['name' => $this->category->name, 'id' => $this->category->id],
            'properties' => $this->properties,
            'category' => $this->category,
            'cities' => $this->cities
        ];
    }
}
