<?php

namespace App\Http\Controllers\Api\V1\Admin\Shop;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Admin\Shop\Product\ProductRequest;
use App\Http\Requests\Api\Admin\Shop\Product\AttachToCityRequest;
use App\Http\Requests\Api\Admin\Shop\Product\RecommendRequest;
use App\Http\Requests\Api\Admin\Shop\Product\UpdateCategoryRequest;
use App\Models\Shop\Product;
use App\Http\Resources\ProductResource;
use App\Services\Shop\ProductService;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service){
        $this->service = $service;
    } //Конструктор

    //
    //  REST API-методы
    //
    public function index(){
        $products = $this->service->getMethods()->with(['cities:id,name', 'categories:id,name', 'optionRecords'])->get();
        return ProductResource::collection($products);
    } //index

    public function show($product){
        $product = $this->service->findById(
            id: $product,
            with: ['cities:id,name', 'categories:id,name', 'optionRecords']
        );

        return new ProductResource($product);
    } //show

    public function store(ProductRequest $request){
        $this->service->create($request);
        return response()->json(['message' => 'Продукт добавлен в базу данных']);
    } //store

    public function update(ProductRequest $request, $product){
        //TODO Разобраться с привязкой моделей
        $product = Product::findOrFail($product);

        $this->service->update($request, $product);
        return response()->json(['message' => 'Данные продукта обновлены']);
    } //update

    public function destroy($product){
        //TODO Разобраться с привязкой моделей
        $product = Product::findOrFail($product);

        $this->service->remove($product);
        return response()->json(['message' => 'Продукт удален']);
    } //destroy

    //
    //      Привязка продуктов к городам
    //
    public function attachToCity(AttachToCityRequest $request){
        $this->service->attachToCity($request);
        return response()->json(['message' => 'Продукт прикреплен к городу']);
    } //attachToCity

    public function detachFromCity(AttachToCityRequest $request){
        $this->service->detachFromCity($request);
        return response()->json(['message' => 'Продукт откреплён от города']);
    } //attachToCity

    public function updateCategory(UpdateCategoryRequest $request){
        $this->service->updateCategory($request);
        return response()->json(['message' => 'Продукт назначен на категорию']);
    } //updateCategory

    //
    //      Список "Часто заказывают"
    //
    public function showRecommended(){
        return ProductResource::collection(
            $this->service->findRecommended()
        );
    } //showRecommended

    public function addToRecommended(RecommendRequest $request){
        $this->service->addToRecommended($request);
        return response('Продукт добавлен в список часто заказываемых', 201);
    } //addToRecommended

    public function removeFromRecommeded(RecommendRequest $request){
        $this->service->removeFromRecommended($request);
        return response('Продукт удалён из списка часто заказываемых', 204);
    } //removeFromRecommeded

    public function clearRecommended(){
        $this->service->clearRecommended();
        return response('Список часто заказываемых очищен', 201);
    } //clearRecommended
}
