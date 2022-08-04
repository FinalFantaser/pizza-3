<?php

namespace App\Services\Shop\Payment;

use App\Http\Requests\Api\Admin\Shop\Payment\Yookassa\CreateRequest;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\ReadRepository\Shop\Payment\Yookasssa\YookassaShopReadRepository;
use App\Repository\Shop\Payment\Yookassa\YookassaShopRepository;

class YookassaShopService{
    public function __construct(
        private YookassaShopRepository $repository,
        private YookassaShopReadRepository $readRepository
    )
    {} //Конструктор

    public function create(CreateRequest $request): YookassaShop
    {
        return $this->repository->create(
            name: $request->name,
            shop_id: $request->shop_id,
            api_token: $request->api_token,
            city_ids: $request->has('city_ids') ? $request->city_ids : []
        );
    } //create

};