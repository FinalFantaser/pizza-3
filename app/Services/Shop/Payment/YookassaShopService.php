<?php

namespace App\Services\Shop\Payment;

use App\Http\Requests\Api\Admin\Shop\Payment\Yookassa\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Payment\Yookassa\UpdateRequest;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\ReadRepository\Shop\Payment\Yookasssa\YookassaShopReadRepository;
use App\Repository\Shop\Payment\Yookassa\YookassaShopRepository;

class YookassaShopService{
    public function __construct(
        private YookassaShopRepository $repository,
        private YookassaShopReadRepository $readRepository
    )
    {} //Конструктор

    //
    //      Управление моделями
    //
    public function create(CreateRequest $request): YookassaShop
    {
        return $this->repository->create(
            name: $request->name,
            shop_id: $request->shop_id,
            api_token: $request->api_token,
            city_ids: $request->city_ids ?? []
        );
    } //create

    public function update(UpdateRequest $request, YookassaShop $shop): YookassaShop
    {
        return $this->repository->update(
            shop: $shop,
            name: $request->name,
            shop_id: $request->shop_id,
            api_token: $request->api_token,
            city_ids: $request->city_ids ?? []
        );
    } //update

    public function remove(YookassaShop $shop): void
    {
        $this->repository->remove($shop);
    } //remove

    //
    //      Поиск в базе данных
    //
    public function findById(int $id, string|array $with = null): YookassaShop
    {
        return $this->readRepository->findById(id: $id, with: $with);
    } //findById

    public function findByCity(int $city_id, string|array $with = null): YookassaShop
    {
        return $this->readRepository->findByCity(city_id: $city_id, with: $with);
    } //findByCity
};