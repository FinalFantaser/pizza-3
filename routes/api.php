<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\ManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->name('api.')->namespace('App\Http\Controllers')->group(function () {
    Route::namespace('Api\V1')->group(function(){
        Route::middleware(['auth:api', 'verified'])->group(function () {

            //Для администраторов
            Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
                //  Пользователи
                Route::apiResource('users', 'UserController');

                //Управление администраторами
                Route::get('admins.index', 'AdminController@index')->name('admins.index');
                Route::post('admins.assign', 'AdminController@assign')->name('admins.assign');
                Route::post('admins.dismiss', 'AdminController@dismiss')->name('admins.dismiss');

                //  Менеджеры по городам
                Route::prefix('managers')->group(function(){
                    Route::get('index', 'ManagerController@index')->name('managers.index');
                    Route::post('assign', 'ManagerController@assign')->name('managers.assign');
                    Route::delete('dismiss', 'ManagerController@dismiss')->name('managers.dismiss');
                });

                Route::namespace('Shop')->group(function(){
                    //  Постеры
                    Route::apiResource('posters', 'PosterController');
                    Route::post('posters.enable/{poster}', 'PosterController@enable')->name('posters.enable');
                    Route::post('posters.disable/{poster}', 'PosterController@disable')->name('posters.disable');
                    Route::post('posters.attach', 'PosterController@attachToCity')->name('posters.attach');
                    Route::post('posters.detach', 'PosterController@detachFromCity')->name('posters.detach');


                    //  Города
                    Route::apiResource('cities', 'CityController')->only(['index', 'store', 'update', 'destroy']);

                    //  Категории
                    Route::apiResource('categories', 'CategoryController')->only(['index', 'store', 'show', 'update', 'destroy']);

                    //  Продукты
                    Route::apiResource('products', 'ProductController');
                    Route::post('products.city.attach', 'ProductController@attachToCity');
                    Route::post('products.city.detach', 'ProductController@detachFromCity');

                    //  Методы доставки
                    Route::apiResource('delivery-methods', 'DeliveryMethodController');

                    //  Управление заказами
                    Route::apiResource('orders', 'OrderController')->only(['index', 'show', 'destroy']);
                    Route::post('orders.cancel/{order}', 'OrderController@cancel')->name('order.cancel');
                    Route::post('orders.pay/{order}', 'OrderController@pay')->name('order.pay');
                    Route::post('orders.send/{order}', 'OrderController@send')->name('order.send');
                    Route::post('orders.complete/{order}', 'OrderController@complete')->name('order.complete');
                });
            });

            //Для менеджеров
            Route::prefix('manager')->name('manager.')->namespace('Manager')->middleware(['auth:api', 'verified', 'manager'])->group(function(){
                Route::namespace('Shop')->group(function(){

                    //  Управление заказами
                    Route::name('orders.')->group(function(){
                        Route::get('orders', 'OrderController@index')->name('index');
                        Route::get('orders/{order}', 'OrderController@show')->name('show');

                        Route::post('orders.cancel/{order}', 'OrderController@cancel')->name('order.cancel');
                        Route::post('orders.pay/{order}', 'OrderController@pay')->name('order.pay');
                        Route::post('orders.send/{order}', 'OrderController@send')->name('order.send');
                        Route::post('orders.complete/{order}', 'OrderController@complete')->name('order.complete');
                    });


                });
            });
        });
    });
});
