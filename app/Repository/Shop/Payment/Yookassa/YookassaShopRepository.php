<?php

namespace App\Repository\Shop\Payment\Yookassa;

use App\Models\Shop\Payment\Yookassa\YookassaShop;
use Illuminate\Support\Facades\DB;

class YookassaShopRepository{
    public function create(?string $name = null, string $shop_id, string $api_token, array $city_ids = []): YookassaShop
    {
        $shop = YookassaShop::create([
            'name' => $name,
            'shop_id' => $shop_id,
            'api_token' => $api_token,
        ]);

        $this->attachToCity(shop_id: $shop->id, city_ids: $city_ids); //Привязка к городам

        return $shop;
    } //create

    public function update(YookassaShop $shop, ?string $name = null, string $shop_id, string $api_token, array $city_ids = []): YookassaShop
    {
        $shop->update([
            'name' => $name,
            'shop_id' => $shop_id,
            'api_token' => $api_token,
        ]);

        $this->clearCities($shop->id); //Отвязка от всех городов
        $this->attachToCity(shop_id: $shop->id, city_ids: $city_ids); //Привязка к городам

        return $shop;
    } //update

    public function remove(YookassaShop $shop): void
    {
        $this->clearCities($shop->id); //Отвязка от всех городов
        $shop->delete();
    } //remove

    public function attachToCity(int $shop_id, array $city_ids): void //Привязать магазин к городам
    {
        $data = array_map(function($city_id) use ($shop_id){
            return [
                'shop_id' => $shop_id,
                'city_id' => $city_id
            ];
        }, $city_ids);

        DB::table('cities_yookassa_shops')->insert($data);
    } //attachToCity

    public function clearCities(int $shop_id): void //Отвязать магазин от всех городов
    {
        DB::table('cities_yookassa_shops')->where('shop_id', $shop_id)->delete();
    } //clearCities
    
};

