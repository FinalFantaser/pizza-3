<?php

namespace Database\Seeders;

use App\Models\Shop\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                [
                    'phone' => '+7 000 000 00 01',
                    'address' => 'Тест',
                    'restaurant_id' => '000016',
                    'zone_id' => '026569',
                ]
            );

            City::updateOrCreate(
                ['name' => 'Балабаново'],
                [
                    'phone' => '+7 000 000 00 02',
                    'address' => 'Тест',
                    'restaurant_id' => '000006',
                    'zone_id' => '026332',
                ]
            );
            
            City::updateOrCreate(
                ['name' => 'Малоярославец'],
                [
                    'phone' => '+7 000 000 00 03',
                    'address' => 'Тест',
                    'restaurant_id' => '000009',
                    'zone_id' => '026334',
                ]
            );
    
            City::updateOrCreate(
                ['name' => 'Жуков'],
                [
                    'phone' => '+7 000 000 00 04',
                    'address' => 'Тест',
                    'restaurant_id' => '000014',
                    'zone_id' => '027612',
                ]
            );
        });
    }
}
