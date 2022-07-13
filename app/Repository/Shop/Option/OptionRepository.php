<?php
    namespace App\Repository\Shop\Option;

    use App\Models\Shop\Option\Option;

    class OptionRepository{
        public function create(string $name, int $type_id, string $items): Option
        {
            return Option::create([
                'name' => $name,
                'type_id' => $type_id,
                'items' => json_decode($items),
            ]);
        } //create

        public function update(Option $option, string $name, int $type_id, string $items): Option
        {
            $option->update([
                'name' => $name,
                'type_id' => $type_id,
                'items' => json_decode($items),
            ]);

            return $option;
        } //update

        public function remove(Option $option){
            $option->delete();
        } //remove
    };