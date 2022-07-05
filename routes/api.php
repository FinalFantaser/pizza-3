<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
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

Route::prefix('v1')->namespace('Api\V1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::prefix('admin')->namespace('Admin')->group(function(){
            //  Пользователи
            Route::apiResource('users', 'UserController');

            //Управление администраторами
            Route::get('admins.index', [AdminController::class, 'index'])->name('admins.index');
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

                //  Города
                Route::apiResource('cities', 'CityController')->only(['index', 'store', 'update', 'destroy']);

                //  Категории
                Route::apiResource('categories', 'CategoryController')->only(['index', 'store', 'show', 'update', 'destroy']);
            });
        });
    });
});
