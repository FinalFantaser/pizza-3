<?php

namespace App\Http\Resources;

use App\Http\Resources\Option\OptionRecordResource;
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
            'slug' => $this->slug,
            'status' => $this->status,
            'price' => $this->price,
            'price_sale' => $this->price_sale,
            'description' => $this->description,
            'imageUrl' => $this->imageUrl('products'),
            'thumbUrl' => $this->imageUrl('products','product_thumb_admin'),
            'imageUrlJpg' => $this->getFirstMediaUrl('products', 'jpg'),
            'imageUrlJpg110' => $this->getFirstMediaUrl('products', 'jpg110'),
            'imageUrlWebp' => $this->getFirstMediaUrl('products', 'webp'),
            'imageUrlWebp110' => $this->getFirstMediaUrl('products', 'webp110'),
            'properties' => $this->properties,
            'category' => $this->whenLoaded('categories', $this->category),
            'cities' => CityResource::collection($this->whenLoaded('cities')),
             //'options' => $this->optionRecords,
            'options' => OptionRecordResource::collection( $this->whenLoaded('optionRecords', $this->optionRecords) ),
        ];
    }
}
