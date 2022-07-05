<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Shop\City;

class Poster extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'description', 'enabled'];
    public $timestamps = false;

    public function registerMediaConversions(Media $media = null): void{
        $this->addMediaConversion('thumb')
            ->crop('crop-top-left', 700, 380);

        $this->addMediaConversion('thumb_admin') //Миниатюра для списка в админке
            ->crop('crop-top-left', 100, 65);
    } //registerMediaConversions

    public function cities(){
        return $this->belongsToMany(City::class, 'posters_cities', 'poster_id', 'city_id');
    } //cities
}
