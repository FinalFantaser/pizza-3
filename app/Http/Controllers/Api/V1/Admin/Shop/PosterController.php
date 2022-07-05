<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;

use App\Models\Shop\Poster;
use App\Http\Resources\PosterResource;
use App\Http\Requests\Api\Admin\Shop\Poster\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Poster\UpdateRequest;
use App\Services\Shop\PosterService;

class PosterController extends Controller
{
    private $service;

    public function __construct(PosterService $service)
    {
        $this->service = $service;
    } //Конструктор

    //
    //                  REST-API
    //
    public function index(){
        return PosterResource::collection(
            $this->service->getMethods()->paginate(20)
        );
    } //index

    public function show(Poster $poster){
        return new PosterResource($poster);
    } //show

    public function store(CreateRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Постер добавлен в базу данных']);
    } //store

    public function update(UpdateRequest $request, Poster $poster){
        $this->service->update($request, $poster);
        return response()->json(['message' => 'Данные постера обновлены']);
    } //store

    public function destroy(Poster $poster){
        $this->service->remove($poster);
        return response()->json(['message' => 'Постер удалён']);
    } //destroy

    //
    //                  Управление постерами
    //  
    public function enable(Poster $poster){
        $this->service->enable($poster);
        return response()->json(['message' => 'Постер виден посетителям']);
    } //enable

    public function disable(Poster $poster){
        $this->service->disable($poster);
        return response()->json(['message' => 'Постер скрыт от посетителей']);
    } //enable
}
