<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Meta;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Shop\Product;

class Category extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia;

    protected $fillable = ['name','meta'];

    protected $casts = [
        'meta' => 'array'
    ];

    //---------------------------------------------------
    //  Методы
    //

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    } //sluggable

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    } //products


    public function imageUrl(string $thumb = null): string
    {
        $url = !$thumb ? $this->getFirstMediaUrl('categoryImages') : $this->getFirstMediaUrl('categoryImages', $thumb);
        return $url;
    } //imageUrl

    public function getTitle(): string
    {
        return $this->meta['name'] ?? $this->name;
    } //getTitle

    public function getRouteKeyName(): string
    {
        if (request()->expectsJson()) {
            return 'id';
        }

        return 'slug';
    } //getRouteKeyName

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb_admin')
            ->width(350)
            ->height(250)
            ->nonOptimized();

        $this->addMediaConversion('thumb')
            ->width(370)
            ->height(370);

        $this->addMediaConversion('categoryImages')
            ->withResponsiveImages();
    } //registerMediaConversions

}
