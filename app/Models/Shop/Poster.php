<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Shop\City;

class Poster extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = ['name', 'description', 'anchor', 'enabled'];
    public $timestamps = false;

    public function scopeActive(Builder $query){
        return $query->where('enabled', self::STATUS_ACTIVE );
    }

    public function registerMediaConversions(Media $media = null): void{
        $this->addMediaConversion('size450')
            ->width(450)
            ->keepOriginalImageFormat();

        $this->addMediaConversion('size_original')
            ->optimize()
            ->keepOriginalImageFormat();

        $this->addMediaConversion('webp')
            ->optimize()
            ->format('webp');

        $this->addMediaConversion('webp450')
            ->width(450)
            ->format('webp');
    } //registerMediaConversions

    public function cities(){
        return $this->belongsToMany(City::class, 'posters_cities', 'poster_id', 'city_id');
    } //cities
}
