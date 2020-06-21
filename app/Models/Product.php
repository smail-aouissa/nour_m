<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model
{
    use HasMediaTrait, SoftDeletes;

    protected $guarded = [];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('small')
            ->nonOptimized()
            ->width(84)
            ->height(84);

        $this->addMediaConversion('medium')
            ->nonOptimized()
            ->width(360)
            ->height(360)
            ->optimize();

        $this->addMediaConversion('full')
            ->optimize()
            ->width(720)
            ->height(720);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('product_images')
            ->useDisk('products');
    }

    public function categories(){
        return $this->belongsTo(Category::class);
    }

    public function color(){
        return $this->hasMany(Color::class);
    }

    public function attributes(){
        return $this->hasMany(Attribute::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}
