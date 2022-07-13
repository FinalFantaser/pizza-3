<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\Shop\Option\OptionRequest;
use App\Http\Resources\Option\OptionResource;
use App\Models\Shop\Option\Option;
use App\Services\Shop\OptionService;

class OptionController extends Controller
{
    public function __construct(
        public OptionService $optionService
    )
    {} //Конструктор

    public function index()
    {
        return OptionResource::collection(
            $this->optionService->getMethods()->get()
        );
    } //index

    public function store(OptionRequest $request)
    {
        $this->optionService->create($request);
        return response('Опция добавлена в базу данных', 201);
    } //store

    public function show(Option $option)
    {
        return new OptionResource($option);
    } //show

    public function update(OptionRequest $request, Option $option)
    {
        $this->optionService->update($request, $option);
        return response('Данные опции обновлены');
    } //update

    public function destroy(Option $option)
    {
        $this->optionService->remove($option);
        return response('Опция удалена');
    } //destroy
}
