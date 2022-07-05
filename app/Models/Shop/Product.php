<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop\City;
use App\Models\Shop\Category;

use App\Http\Controllers\Meta;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia;

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';

    protected $fillable = ['name', 'status','price','price_sale','description','meta','tags','properties'];

    protected $casts = [
        'meta' => 'array',
        'tags' => 'array',
        'properties' => 'array',
    ];

    /**
     * @param $name
     * @param Meta $meta
     * @property string $status
     */

    public function getTitle(): string
    {
        return $this->meta['name'] ?? $this->name;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }

        $this->update([
            'status' => self::STATUS_DRAFT
        ]);
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function cities(){
        return $this->belongsToMany(City::class, 'product_city', 'city_id', 'product_id');
    }

    public function getPrice()
    {
        if ($this->price_sale) {
            return ['price_sale' => $this->price_sale, 'price' => $this->price] ;
        }
        return $this->price;
    }

    public function reduceQuantity(): void
    {
        $quantity = $this->quantity;

        $this->update([
            'quantity' => $quantity - 1
        ]);
    }

    public function getWeightOrPieces(): string
    {
        return $this->properties['weight'] ? $this->properties['weight'] . ' г' : $this->properties['pieces'] . ' шт';
    }

    public function canBeOrdered(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getRouteKeyName(): string
    {
        if (request()->expectsJson()) {
            return 'id';
        }

        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function imageUrl(string $thumb = null): string
    {
        return  !$thumb ? $this->getFirstMediaUrl('product') : $this->getFirstMediaUrl('product', $thumb);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('product_thumb_admin')
            ->width(350)
            ->height(250)
            ->nonOptimized();

        $this
            ->addMediaConversion('product')
            ->withResponsiveImages();
    }
}
