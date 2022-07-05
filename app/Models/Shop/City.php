<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

use App\Models\Shop\Poster;
use App\Models\Shop\Product;

class City extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'slug'];

    public function sluggable(): array //Преобразование названия города в латиницу
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    } //sluggable

    public function products(){
        return $this->belongsToMany(Product::class, 'product_city', 'city_id', 'product_id');
    } //products

    public function posters(){
        return $this->belongsToMany(Poster::class, 'posters_cities', 'city_id', 'poster_id');
    } //posters
}
