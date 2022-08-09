<?php

use App\Models\MediaLibrary;
use App\Models\Role;
use App\Models\Shop\Category;
use App\Models\Shop\City;
use App\Models\Shop\Delivery\DeliveryMethod;
use App\Models\Shop\Delivery\PickupPoint;
use App\Models\Shop\Option\Option;
use App\Models\Shop\Option\OptionType;
use App\Models\Shop\Order\CustomerData;
use App\Models\Shop\Order\Order;
use App\Models\Shop\Order\OrderItem;
use App\Models\Shop\Poster;
use App\Models\Shop\Product;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Роли
        $role_admin = Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);

        // MediaLibrary
        MediaLibrary::firstOrCreate([]);

        //Города
        $city_aprelevka = City::firstOrCreate([
            'name' => 'Апрелевка'],
            [
                'phone' => '+7 000 000 00 01',
                'address' => 'Тест',
                'restaurant_id' => '000232',
                'zone_id' => '026569',
            ]
        );

        $city_balabanovka = City::firstOrCreate(
            ['name' => 'Балабаново'],
            [
                'phone' => '+7 000 000 00 02',
                'address' => 'Тест',
                'restaurant_id' => '000319',
                'zone_id' => '026332',
            ]
        );
        
        City::firstOrCreate(
            ['name' => 'Малоярославец'],
            [
                'phone' => '+7 000 000 00 03',
                'address' => 'Тест',
                'restaurant_id' => '000434',
                'zone_id' => '026334',
            ]
        );

        City::firstOrCreate(
            ['name' => 'Жуков'],
            [
                'phone' => '+7 000 000 00 04',
                'address' => 'Тест',
                'restaurant_id' => '000807',
                'zone_id' => '027612',
            ]
        );

        // Пользователи
        $user = User::firstOrCreate( //Главный админ
            ['email' => '1@1.ru'],
            [
                'name' => 'pizza_admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );

        $manager_1 = User::firstOrCreate( //Менеджер по Апрелевке
            ['email' => 'aprelevo@manager.ru'],
            [
                'name' => 'aprelevo_manager',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );

        $manager_2 = User::firstOrCreate( //Менеджер по Балабаново
            ['email' => 'balabanovo@manager.ru'],
            [
                'name' => 'balabanovo_manager',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );

        //Категории
        $category = Category::firstOrCreate(['name' => 'Тестовая категория']);

        //Продукты
        $product = Product::firstOrCreate(
            ['name' => 'Тестовый продукт'],
            [
                'status' => Product::STATUS_ACTIVE,
                'price' => 100,
                'price_sale' => 95,
                'description' => 'Описание тестового продукта',
                'meta' => null,
                'tags' => '["test", "product"]',
                'properties' => '{"weight": 500, "quantity": 1, "structure": "Информация о составе"}'
            ]
        );

        //Типы опций
        $option_list = OptionType::firstOrCreate(
            ['name' => 'Список']
        );

        $option_radio = OptionType::firstOrCreate(
            ['name' => 'Переключатель']
        );

        $option_checkbox = OptionType::firstOrCreate(
            ['name' => 'Чекбокс']
        );

        //Опции
        //...
        //...

        //Постеры
        $poster = Poster::firstOrCreate(
            ['name' => 'Тестовый постер'],
            [
                'description' => 'Описание тестового постера',
                'enabled' => true,
            ]
        );

        //Способы доставки
        $delivery_method = DeliveryMethod::firstOrCreate(
            ['name' => 'Доставка курьером'],
            [
                'type' => DeliveryMethod::TYPE_COURIER,
                'cost' => 100,
                'free_from' => 600,
                'min_weight' => 100,
                'max_weight' => 500,
            ]
        );

        $delivery_method2 = DeliveryMethod::firstOrCreate(
            ['name' => 'Самовывоз'],
            [
                'type' => DeliveryMethod::TYPE_PICKUP,
                'cost' => 0,
                'free_from' => 1,
                'min_weight' => 100,
                'max_weight' => 500,
            ]
        );

        //Пункты самовывоза
        $pickup_point = PickupPoint::firstOrCreate(
            ['city_id' => $city_aprelevka->id],
            ['address' => 'Тестовый пункт выдачи']
        );

        //Заказы
        $customer_data = CustomerData::firstOrCreate(
            ['name' => 'Тестовый клиент'],
            [
                'email' => 'test@mail.ru',
                'phone' => '80001112233',
                'city_id' => $city_balabanovka->id,
                'address' => 'Адрес доставки заказа тестовому клиенту',
                'order_id' => 1,
            ]
        );

        $order = Order::firstOrCreate(
            ['customer_data_id' => $customer_data->id],
            [
                'delivery_method_id' => $delivery_method->id,
                'delivery_method_name' => $delivery_method->name,
                'delivery_method_cost' => $delivery_method->cost,
                'cost' => 1500,
                'note' => 'Тестовый заказ',
                'current_status' => 'completed',
                'token' => Str::random(60),
            ]
        );

        $order_item = OrderItem::firstOrCreate(
            ['order_id' => $order->id],
            [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_quantity' => 3,
                'total_price' => $product->price * 3,
            ]
        );

        //Построение отношений между моделями
        $user->roles()->sync([$role_admin->id]);

        $manager_1->cities()->attach($city_aprelevka);
        $manager_2->cities()->attach($city_balabanovka);

        $category->products()->attach($product);

        $poster->cities()->attach($city_balabanovka);

        $city_balabanovka->products()->attach($product);

//        // API tokens
//        User::where('api_token', null)->get()->each->update([
//            'api_token' => Token::generate()
//        ]);
    }
}
