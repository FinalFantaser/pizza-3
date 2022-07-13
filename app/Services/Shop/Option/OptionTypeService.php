<?php

namespace App\Services\Shop\Option;

use App\Http\Requests\Api\Admin\Shop\Option\OptionTypeRequest;
use App\Models\Shop\Option\OptionType;
use App\ReadRepository\Shop\Option\OptionTypeReadRepository;
use App\Repository\Shop\Option\OptionTypeRepository;

class OptionTypeService{
    public function __construct(
        public OptionTypeRepository $optionTypeRepository,
        public OptionTypeReadRepository $optionTypeReadRepository
    )
    {} //Конструктор

    //
    //  CRUD-методы
    //
    public function create(OptionTypeRequest $request): OptionType
    {
        return $this->optionTypeRepository->create($request->name);
    } //create

    public function update(OptionTypeRequest $request, OptionType $optionType): OptionType
    {
        return $this->optionTypeRepository->update($optionType, $request->name);
    } //update

    public function remove(OptionType $optionType): void
    {
        $this->optionTypeRepository->remove($optionType);
    } //remove

    //
    //  Методы для запросов
    //
    public function getMethods()
    {
        return $this->optionTypeReadRepository->getMethods();
    } //getMethods

    public function findById(int $id): OptionType
    {
        return $this->optionTypeReadRepository->findById($id);
    } //findById
};