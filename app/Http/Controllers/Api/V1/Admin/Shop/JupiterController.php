<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Jupiter\Create\OptionRequest;
use App\Http\Requests\Api\Admin\Shop\Jupiter\Create\ProductRequest;
use App\Http\Resources\JupiterRecordResource;
use App\Services\Shop\JupiterService;
use \Illuminate\Http\Response;

class JupiterController extends Controller
{
    public function __construct(
        private JupiterService $jupiterService
    )
    {} //Конструктор

    public function index()
    {
        return $this->jupiterService->findAll();
    } //index

    public function addProduct(ProductRequest $request): Response
    {
        $this->jupiterService->addProduct(
            name: $request->name,
            price: $request->price,
            product_id: $request->product_id,
            jupiter_id: $request->jupiter_id,
            option_id: $request->option_id,
            option_selected: $request->option_selected
        );

        return response('Продукт добавлен в таблицу', 201);
    } //addProduct

    public function addOption(OptionRequest $request): Response
    {
        $this->jupiterService->addOption(
            name: $request->name,
            price: $request->price,
            jupiter_id: $request->jupiter_id,
            option_id: $request->option_id,
            option_selected: $request->option_selected
        );

        return response('Опция добавлена в таблицу', 201);
    } //addOption

    public function remove(int $id): Response
    {
        $this->jupiterService->remove($id);
        return response('Запись удалена');
    } //remove
}
