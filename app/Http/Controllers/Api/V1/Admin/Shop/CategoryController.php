<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use App\Http\Requests\Api\Admin\Shop\Category\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Category\UpdateRequest;

use App\Models\Shop\Category;
use App\Http\Resources\CategoryResource;
use App\Services\Shop\CategoryService;

class CategoryController extends Controller
{
    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    } //Конструктор

    public function index(){
        $categories = $this->service->getMethods()->get();
        return CategoryResource::collection($categories);
    } //index

    public function show(int $category){
        //TODO Разобраться с проблемой привязки моделей
        $category = Category::findOrFail($category);

        return new CategoryResource($category);
    } //show

    public function store(CreateRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Категория создана']);
    } //store

    public function update(UpdateRequest $request, int $category){
        //TODO Разобраться с проблемой привязки моделей
        $category = Category::findOrFail($category);

        $this->service->update($request, $category);
        return response()->json(['message' => 'Данные категории обновлены']);
    } //update

    public function destroy(int $category){
        //TODO Разобраться с проблемой привязки моделей
        $category = Category::findOrFail($category);

        $category->delete();
        return response()->json(['message' => 'Категория удалена']);
    } //destroy
}
