<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Payment\Yookassa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Payment\Yookassa\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Payment\Yookassa\UpdateRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\Payment\Yookassa\YookassaShopResource;
use App\Models\Shop\Payment\Yookassa\YookassaShop;
use App\Services\Shop\CityService;
use App\Services\Shop\Payment\Yookassa\YookassaShopService;
use Illuminate\Support\Facades\DB;

class YookassaShopController extends Controller
{
    public function __construct(
        private YookassaShopService $service,
        private CityService $cityService
    )
    {} //Конструктор

    public function index()
    {
        return YookassaShopResource::collection(
            $this->service->getMethods('cities')->paginate(50)
        );
    }

    public function store(CreateRequest $request)
    {
        $this->service->create($request);
        return response('Магазин Yookassa добавлен в базу данных', 201);
    }

    public function show(YookassaShop $yookassa_shop)
    {
        $yookassa_shop->load('cities');
        return new YookassaShopResource($yookassa_shop);
    }

    public function update(UpdateRequest $request, YookassaShop $yookassa_shop)
    {
        $this->service->update(request: $request, shop: $yookassa_shop);
        return response('Магазин Yookassa обовлен', 200);
    }

    public function destroy(YookassaShop $yookassaShop)
    {
        $this->service->remove($yookassaShop);
        return response('Магазин Yookassa удалён', 204);
    }

    public function freeCities() //Показать города, которые ещё не привязаны к магазинам ЮKassa
    {
        //Получение идентификаторов городов, привязанных к магазинам
        $ids = DB::table('cities_yookassa_shops')->select(['city_id'])->pluck('city_id');

        //Загрузка городов, не входящих в полученный список
        $cities = $this->cityService->getMethods()->whereNotIn('id', $ids)->get();

        return CityResource::collection($cities);

    } //freeCities
}
