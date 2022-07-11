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
            // 'cities' => $this->cities,
            'imageUrl' => $this->imageUrl('product'),
            'thumbUrl' => $this->imageUrl('product_thumb_admin'),
            'properties' => $this->properties,
            'cities' => $this->cities
        ];
    }
}
