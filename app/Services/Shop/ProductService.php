<?php
    namespace App\Services\Shop;

    use App\Models\Shop\City;
    use App\Models\Shop\Category;
    use App\Models\Shop\Product;
    use App\Http\Requests\Api\Admin\Shop\Product\ProductRequest;
    use App\Http\Requests\Api\Admin\Shop\Product\AttachToCityRequest;
use App\Http\Requests\Api\Admin\Shop\Product\RecommendRequest;
use App\Http\Requests\Api\Admin\Shop\Product\UpdateCategoryRequest;
use App\ReadRepository\Shop\Option\OptionRecordReadRepository;
use App\ReadRepository\Shop\Order\OrderItemReadRepository;
use App\Repository\Shop\ProductRepository;
    use App\ReadRepository\Shop\ProductReadRepository;
use App\Repository\Shop\Option\OptionRecordRepository;

    class ProductService{
        public function __construct(
            private ProductRepository $productRepository,
            private ProductReadRepository $productReadRepository,
            private OptionRecordRepository $optionRecordRepository,
            private OptionRecordReadRepository $optionRecordReadRepository,
            private OrderItemReadRepository $orderItemReadRepository
        )
        {} //Конструктор

        public function create(ProductRequest $request){
            $product = $this->productRepository->create(
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

            $this->addOptions($product, $request);

            return $product;
        } //create

        public function update(ProductRequest $request, Product $product){
            $product = $this->productRepository->update(
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
                $this->addOptions($product, $request);

            return $product;
        } //update

        public function remove(Product $product){
            $this->optionRecordRepository->removeByProduct($product); //Удаление опций, связанных с продуктом
            $this->productRepository->remove($product);
        } //remove


        //
        //  Управление продуктами
        //
        public function updateCategory(UpdateCategoryRequest $request){
            $this->productRepository->updateCategory(
                Product::findOrFail($request->product_id),
                Category::findOrFail($request->category_id)
            );
        } //updateCategory

        public function attachToCity(AttachToCityRequest $request){
            $this->productRepository->attachToCity(
                Product::findOrFail($request->product_id),
                $request->city_id
            );
        } //attachToCity

        public function detachFromCity(AttachToCityRequest $request){
            $this->productRepository->detachFromCity(
                Product::findOrFail($request->product_id),
                $request->city_id
            );
        } //detachFromCity

        public function clearAllOptions(Product $product){ //Удалить все опции у продукта
            $this->optionRecordRepository->removeByProduct($product);
        } //clearAllOptions

        public function addOptions(Product $product, ProductRequest $request){
            $this->clearAllOptions($product); //Удаление старых опций
            if(!$request->has('options'))
                return;

            //Создание массива
            $data = array_map(function($value) use ($product) {
                return array_merge(
                    $value, //Добавление идентификатора продукта
                    [
                        'product_id' => $product->id,
                        // 'items' => json_decode($value['items']),
                    ]);
            }, json_decode(json: $request->options, associative: true) );

            $this->optionRecordRepository->createBulk(
                $data
            );
        } //addOptions

        public function addToRecommended(RecommendRequest $request): void
        {
            $this->productRepository->addToRecommended(product_id: $request->product_id, sort: $request->sort ?? 0);
        } //addToRecommended

        public function removeFromRecommended(RecommendRequest $request): void
        {
            $this->productRepository->removeFromRecommended($request->product_id);
        } //removeFromRecommended

        public function clearRecommended(): void
        {
            $this->productRepository->clearRecommended();
        } //clearRecommended

        //
        // Поисковые запросы
        //
        public function getMethods(){
            return $this->productReadRepository->getMethods();
        } //getMethods

        public function findById(int|array $id, string|array $with = null){
            return $this->productReadRepository->findById(
                id: $id,
                with: $with
            );
        } //findById

        public function findByCity(City $city){
            return $this->productReadRepository->findByCity($city);
        } //findByCity

        public function findByCityAndCategory(City $city, Category $category, string|array $with = null){
            return $this->productReadRepository->findByCityAndCategory($city, $category, $with);
        } //findByCityAndCategory

        public function findPopular(int $limit = 8){ //Посчитать наиболее часто заказываемые продукты по статистике заказов
            return $this->orderItemReadRepository->popular(limit: $limit)->pluck('product');
        } //findPopular
        
        public function findRecommended(){ //Показать продукты из таблицы "Часто заказывают"
            return $this->productReadRepository->findRecommended();
        } //findRecommended
    }