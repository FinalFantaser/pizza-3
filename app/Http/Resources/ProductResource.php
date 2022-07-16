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
            'imageUrl' => $this->imageUrl('product'),
            'thumbUrl' => $this->imageUrl('product_thumb_admin'),
            'properties' => $this->properties,
            'category' => $this->whenLoaded('categories', $this->category),
            'cities' => $this->whenLoaded('cities'),
             //'options' => $this->optionRecords,
            'options' => OptionRecordResource::collection( $this->whenLoaded('optionRecords', $this->optionRecords) ),
        ];
    }
}
