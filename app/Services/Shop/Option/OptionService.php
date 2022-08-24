<?php

namespace App\Services\Shop\Option;

use App\Http\Requests\Api\Admin\Shop\Option\OptionRequest;
use App\Models\Shop\Option\Option;
use App\ReadRepository\Shop\Option\OptionReadRepository;
use App\Repository\Shop\Option\OptionRepository;

class OptionService{
    public function __construct(
        public OptionRepository $optionRepository,
        public OptionReadRepository $optionReadRepository
    )
    {} //Конструктор

    //
    // CRUD-методы
    //
    public function create(OptionRequest $request): Option
    {
        return $this->optionRepository->create(
            name: $request->name,
            checkout_type: $request->checkout_type,
            type_id: $request->type_id,
            items: $request->items
        );
    } //create

    public function update(OptionRequest $request, Option $option): Option
    {
        return $this->optionRepository->update(
            option: $option,
            name: $request->name,
            checkout_type: $request->checkout_type,
            type_id: $request->type_id,
            items: $request->items
        );
    } //update

    public function remove(Option $option): void
    {
        $this->optionRepository->remove($option);
    } //remove

    //
    // Методы для поиска
    //
    public function getMethods()
    {
        return $this->optionReadRepository->getMethods();
    } //getMethods

    public function findById(int $id): Option
    {
        return $this->optionReadRepository->findById($id);
    } //findById
};