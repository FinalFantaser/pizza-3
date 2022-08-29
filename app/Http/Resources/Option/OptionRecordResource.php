<?php

namespace App\Http\Resources\Option;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionRecordResource extends JsonResource
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
            'product_id' => $this->product_id,
            'items' => $this->items,
            // 'parent' => new OptionResource($this->option),
            'parent' => [
                'id' => $this->option->id,
                'name' => $this->option->name,
                'type' => $this->option->type,
                'checkout_type' => $this->option->checkout_type,
            ],
        ];
    }
}
