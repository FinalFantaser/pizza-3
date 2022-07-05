<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\Shop\City\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\City\UpdateRequest;

use App\Models\Shop\City;
use App\Services\Shop\CityService;
use App\Http\Resources\CityResource;

class CityController extends Controller
{
    private $service;

    public function __construct(CityService $service){
        $this->service = $service;
    } //Конструктор

    public function index(){
        $query = $this->service->getMethods();
        return CityResource::collection($query->get());
    } //index

    public function store(CreateRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Город добавлен']);
    } //store

    public function update(UpdateRequest $request, City $city){
        $this->service->update($request, $city);
        return response()->json(['message' => 'Данные города обновлены']);
    } //update

    public function destroy(City $city){
        $this->service->remove($city);
        return response()->json(['message' => 'Город удалён']);
    } //destroy
}
