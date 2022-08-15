<?php

namespace Database\Seeders;

use App\Models\Shop\Delivery\DeliveryMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverMethodSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryMethod::firstOrCreate(
            ['name' => 'Доставка курьером'],
            [
                'type' => DeliveryMethod::TYPE_COURIER,
                'cost' => 100,
                'free_from' => 600,
                'min_weight' => 100,
                'max_weight' => 500,
            ]
        );

        DeliveryMethod::firstOrCreate(
            ['name' => 'Самовывоз'],
            [
                'type' => DeliveryMethod::TYPE_PICKUP,
                'cost' => 0,
                'free_from' => 1,
                'min_weight' => 100,
                'max_weight' => 500,
            ]
        );
    }
}
