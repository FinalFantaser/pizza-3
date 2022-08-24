<?php

namespace App\Http\Resources\Option;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'checkout_type' => $this->checkout_type,
            'type' => new OptionTypeResource($this->type),
            'items' => $this->items,
        ];
    }
}
