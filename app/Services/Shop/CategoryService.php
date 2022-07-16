<?php

namespace App\Services\Shop;

use App\Http\Requests\Api\Admin\Shop\Category\CreateRequest;
use App\Http\Requests\Api\Admin\Shop\Category\UpdateRequest;

use Illuminate\Http\Request;

use App\Models\Shop\Category;

use App\Repository\Shop\CategoryRepository;
use App\ReadRepository\Shop\CategoryReadRepository;

class CategoryService{
    private $repository;
    private $readRepository;

    public function __construct(CategoryRepository $repository, CategoryReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    public function create(CreateRequest $request){
        return
            $this->repository->create(
               name: $request->name,
               sort: $request->sort,
               seo_title: $request->seo_title,
               seo_description: $request->seo_description,
               seo_keywords: $request->seo_keywords
            );
    } //create

    public function update(UpdateRequest $request, Category $category){
        return
            $this->repository->update(
                $category,
                name: $request->name,
                sort: $request->sort,
                seo_title: $request->seo_title,
                seo_description: $request->seo_description,
                seo_keywords: $request->seo_keywords
            );
    } //update

    public function remove(Category $category){
        $this->repository->remove($category);
    } //remove

    public function getMethods(){
        return $this->readRepository->getMethods();
    } //getMethods

    public function findAll(){
        return $this->readRepository->findAll();
    } //findAll
}
