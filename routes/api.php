<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\ManagerController;
use App\Models\Shop\Category;
use App\Models\Shop\City;
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
        Route::middleware(['auth:sanctum'])->group(function () {


            //Для администраторов
            Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
                Route::apiResource('media', \App\Http\Controllers\Api\V1\Admin\MediaLibraryController::class)->only('store', 'show', 'destroy');

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
                    Route::apiResource('cities', \App\Http\Controllers\Api\V1\Admin\Shop\CityController::class);


                    //  Категории
                    Route::apiResource('categories', \App\Http\Controllers\Api\V1\Admin\Shop\CategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

                    //  Продукты
                    Route::apiResource('products', \App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class);
                    Route::post('products.category.update', 'ProductController@updateCategory')->name('products.update.category');
                    Route::post('products.city.attach', [\App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class,'attachToCity']);
                    Route::post('products.city.detach', [\App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class,'detachFromCity']);

                    Route::post('products.activate/{product}', [\App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class,'activate']);
                    Route::post('products.draft/{product}', [\App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class,'draft']);

                    Route::get('products.popular', [\App\Http\Controllers\Api\V1\Admin\Shop\ProductController::class,'popular']);
                    
                    Route::get('products.recommended.index', 'ProductController@showRecommended')->name('recommended.index');
                    Route::post('products.recommended.add', 'ProductController@addToRecommended')->name('recommended.add');
                    Route::post('products.recommended.remove', 'ProductController@removeFromRecommeded')->name('recommended.remove');
                    Route::post('products.recommended.clear', 'ProductController@clearRecommended')->name('recommended.clear');

                    //  Опции
                    Route::namespace('Option')->group(function(){
                        Route::apiResources([
                            'option_types' => 'OptionTypeController',
                            'options' => 'OptionController',
                        ]);
                    });

                    //  Методы доставки
                    Route::prefix('delivery')->namespace('Delivery')->group(function(){
                        Route::apiResources([
                            'methods' => 'DeliveryMethodController',
                            'pickup_points' => 'PickupPointController',
                            'zones' => 'DeliveryZoneController',
                        ]);
                        Route::get('pickup_points/city/{city_id}', 'PickupPointController@listByCity')->name('pickup_points.by_city');
                        Route::get('zones/city/{city_slug}', 'DeliveryZoneController@byCity')->name('zones.by_city');
                    });

                    //  Управление заказами
                    Route::apiResource('orders', 'OrderController')->only(['index', 'show', 'destroy']);
                    Route::post('orders.cancel/{order}', 'OrderController@cancel')->name('order.cancel');
                    Route::post('orders.pay', 'OrderController@pay')->name('order.pay');
                    Route::post('orders.send/{order}', 'OrderController@send')->name('order.send');
                    Route::post('orders.complete/{order}', 'OrderController@complete')->name('order.complete');

                    //Оплата
                    Route::prefix('payment')->namespace('Payment')->name('payment.')->group(function(){
                        Route::apiResource('method', \App\Http\Controllers\Api\V1\Admin\Shop\Payment\PaymentMethodController::class)->only(['index', 'show', 'store', 'destroy']);

                        Route::namespace('Yookassa')->group(function(){
                            Route::apiResources([
                                'yookassa_shop' => \App\Http\Controllers\Api\V1\Admin\Shop\Payment\Yookassa\YookassaShopController::class,
                            ]);
                            Route::get('yookassa_shop.free_cities', [\App\Http\Controllers\Api\V1\Admin\Shop\Payment\Yookassa\YookassaShopController::class, 'freeCities'])->name('yookassa_shop.free_cities');
                        });
                    });
                });
            });

            //Для менеджеров
            Route::prefix('manager')->name('manager.')->namespace('Manager')->middleware(['manager'])->group(function(){
                Route::namespace('Shop')->group(function(){

                    //  Управление заказами
                    Route::name('orders.')->group(function(){
                        Route::get('orders', 'OrderController@index')->name('index');
                        Route::get('orders/{order}', 'OrderController@show')->name('show');

                        Route::post('orders.pay', 'OrderController@pay')->name('order.pay');
                        Route::post('orders.cancel/{order}', 'OrderController@cancel')->name('order.cancel');
                        Route::post('orders.send/{order}', 'OrderController@send')->name('order.send');
                        Route::post('orders.complete/{order}', 'OrderController@complete')->name('order.complete');
                    });


                });
            });
        });

        //Гостевая страница
        Route::prefix('home')->name('home.')->namespace('Home')->group(function(){
            Route::namespace('Shop')->group(function(){
                //Заказы
                Route::name('orders.')->group(function(){
                    Route::post('orders.checkout', 'OrderController@checkout')->name('checkout');
                    Route::get('orders.show', 'OrderController@show')->name('show');
                });

                //Оплата
                Route::prefix('payment')->namespace('Payment')->name('payment.')->group(function(){
                    Route::get('methods', \App\Http\Controllers\Api\V1\Home\Shop\Payment\ShowMethodsController::class)->name('methods');
                    Route::post('yookassa', \App\Http\Controllers\Api\V1\Home\Shop\Payment\YookassaController::class)->name('yookassa');
                    Route::post('yookassa.webhook', \App\Http\Controllers\Api\V1\Home\Shop\Payment\YookassaWebhookController::class)->name('yookassa.webhook');
                });

                //Доставка
                Route::name('delivery.')->prefix('delivery')->namespace('Delivery')->group(function(){
                    Route::get('methods', 'ShowDeliveryMethodsController')->name('methods');
                    Route::get('pickup_points', 'ShowPickupPointsController')->name('pickup_points');
                    Route::get('zones', 'ShowDeliveryZonesController')->name('zones');
                });

                Route::get('cities', 'ShowCitiesController')->name('cities');
                Route::get('categories', 'ShowCategoriesController')->name('categories');
                Route::get('posters', 'ShowPostersController')->name('posters');

                Route::get('{category:slug}', 'ProductController@index')->name('index');

                //Продукты
                Route::name('product.')->prefix('product')->group(function(){
                    Route::get('recommended', 'ProductController@recommended')->name('recommended');
                    Route::get('{product:slug}', 'ProductController@show')->name('show');
                });
            });
        });

        Route::get('token', function (){ return response()->json([ 'token' => csrf_token()], 200); });

    });
});

//Route::fallback( function (){
//    return response()->json(['message' => 'Bad Request, not found this route'], 400);
//});
