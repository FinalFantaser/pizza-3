<?php

namespace App\Models\Shop;

class JupiterRecord{
    public ?array $options;

    public function __construct(
        public int $id,
        public ?int $parent_id,
        public string $name,
        public int $price
    ){
        $this->options = null;
    } //Конструктор

    public function addOption(self $option): void
    {
        $this->options[] = $option;
    } //addOption

    public function options(): array
    {
        return $this->options;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    } //toArray
};