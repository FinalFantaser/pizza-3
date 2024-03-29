<?php

namespace Database\Seeders;

use App\Models\Shop\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class CitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function(){
            City::updateOrCreate(
                ['name' => 'Апрелевка'],
                ['phone' => '+7 000 000 00 01',
                    'address' => 'Тест',
//                    'restaurant_id' => '000016',
//                    'zone_id' => '026569',
                'slug' => \Illuminate\Support\Str::slug('Апрелевка')
                ]
            );

            City::updateOrCreate(
                ['name' => 'Балабаново'],
                ['phone' => '+7 000 000 00 02',
                    'address' => 'Тест',
//                    'restaurant_id' => '000006',
//                    'zone_id' => '026332',
                    'slug' => \Illuminate\Support\Str::slug('Балабаново')
                ]
            );

            City::updateOrCreate(
                ['name' => 'Малоярославец'],
                ['phone' => '+7 000 000 00 03',
                    'address' => 'Тест',
//                    'restaurant_id' => '000009',
//                    'zone_id' => '026334',
                    'slug' => \Illuminate\Support\Str::slug('Малоярославец')
                ]
            );

            City::updateOrCreate(
                ['name' => 'Жуков'],
                ['phone' => '+7 000 000 00 04',
                    'address' => 'Тест',
//                    'restaurant_id' => '000014',
//                    'zone_id' => '027612',
                    'slug' => \Illuminate\Support\Str::slug('Жуков')
                ]
            );

            City::updateOrCreate(
                ['name' => 'Обнинск'],
                ['phone' => '+7 000 000 00 05',
                    'address' => 'Тест',
//                    'restaurant_id' => '000014',
//                    'zone_id' => '027612',
                    'slug' => \Illuminate\Support\Str::slug('Обнинск')
                ]
            );
        });
    }
}
