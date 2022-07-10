<?php
    namespace App\Services\Shop;

    use App\Models\Shop\City;
    use App\Models\Shop\Category;
    use App\Models\Shop\Product;
    use App\Http\Requests\Api\Admin\Shop\Product\ProductRequest;
    use App\Http\Requests\Api\Admin\Shop\Product\AttachToCityRequest;
    use App\Http\Requests\Api\Admin\Shop\Product\UpdateCategoryRequest;
    use App\Repository\Shop\ProductRepository;
    use App\ReadRepository\Shop\ProductReadRepository;

    class ProductService{
        private $repository;
        private $readRepository;

        public function __construct(ProductRepository $repository, ProductReadRepository $readRepository){
            $this->repository = $repository;
            $this->readRepository = $readRepository;
        } //Конструктор

        public function create(ProductRequest $request){
            return $this->repository->create(
                $request->name, 
                $request->price,
                $request->price_sale,
                $request->description,
                $request->tags,
                $request->properties,
                $request->seo_title,
                $request->seo_description,
                $request->seo_keywords,
                $request->category_id,
                $request->city_id ?? null
            );
        } //create

        public function update(ProductRequest $request, Product $product){
            return $this->repository->update(
                $product,
                $request->name, 
                $request->price,
                $request->price_sale,
                $request->description,
                $request->tags,
                $request->properties,
                $request->seo_title,
                $request->seo_description,
                $request->seo_keywords,
                $request->category_id,
                $request->city_id ?? null
            );
        } //update

        public function remove(Product $product){
            $this->repository->remove($product);
        } //remove

        public function updateCategory(UpdateCategoryRequest $request){
            $this->repository->updateCategory(
                Product::findOrFail($request->product_id),
                Category::findOrFail($request->category_id)
            );
        } //updateCategory

        public function attachToCity(AttachToCityRequest $request){
            $this->repository->attachToCity(
                Product::findOrFail($request->product_id),
                City::findOrFail($request->city_id)
            );
        } //attachToCity

        public function detachFromCity(AttachToCityRequest $request){
            $this->repository->detachFromCity(
                Product::findOrFail($request->product_id),
                City::findOrFail($request->city_id)
            );
        } //detachFromCity

        public function getMethods(){
            return $this->readRepository->getMethods();
        } //getMethods

        public function findById(int $id){
            return $this->readRepository->findById($id);
        } //findById

        public function findByCity(City $city){
            return $this->readRepository->findByCity($city);
        } //findByCity

        public function findByCityAndCategory(City $city, Category $category){
            return $this->readRepository->findByCityAndCategory($city, $category);
        } //findByCityAndCategory
    }