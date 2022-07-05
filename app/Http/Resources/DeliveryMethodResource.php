<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryMethodResource extends JsonResource
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
            'name' => $this->name,
            'cost' => $this->cost,
            'free_from' => $this->free_from,
            'min_weight' => $this->min_weight,
            'max_weight' => $this->max_weight,
            'sort' => $this->sort,
        ];
    }
}
