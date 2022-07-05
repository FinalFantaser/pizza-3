<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $category = Category::find($category);

        return new CategoryResource($category);
    } //show

    public function store(Request $request){
        $this->service->create($request);
        return response()->json(['message' => 'Категория создана']);
    } //store

    public function update(Request $request, int $category){
        //TODO Разобраться с проблемой привязки моделей
        $category = Category::find($category);

        $this->service->update($request, $category);
        return response()->json(['message' => 'Данные категории обновлены']);
    } //update

    public function destroy(int $category){
        //TODO Разобраться с проблемой привязки моделей
        $category = Category::find($category);
        
        $category->delete();
        return response()->json(['message' => 'Категория удалена']); 
    } //destroy
}
