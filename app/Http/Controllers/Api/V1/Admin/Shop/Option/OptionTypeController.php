<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop\Option;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Shop\Option\OptionTypeRequest;
use App\Http\Resources\Option\OptionTypeResource;
use App\Models\Shop\Option\OptionType;
use App\Services\Shop\Option\OptionTypeService;
use Illuminate\Http\Request;

class OptionTypeController extends Controller
{
    public function __construct(
        private OptionTypeService $optionTypeService
    )
    {} //Конструктор

    public function index()
    {
        return OptionTypeResource::collection(
            $this->optionTypeService->getMethods()->get()
        );
    } //index

    public function store(OptionTypeRequest $request)
    {
        $this->optionTypeService->create($request);
        return response('Тип добавлен в базу данных', 201);
    } //store

    public function show(OptionType $optionType)
    {
        return new OptionTypeResource($optionType);
    } //show

    public function update(OptionTypeRequest $request, OptionType $optionType)
    {
        $this->optionTypeService->update($request, $optionType);
        return response('Тип обновлён');
    } //update

    public function destroy(OptionType $optionType)
    {
        $this->optionTypeService->remove($optionType);
        return response('Тип удалён');
    } //destroy
}
