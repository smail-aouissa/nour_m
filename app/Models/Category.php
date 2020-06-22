<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->nonOptimized()
            ->width(84)
            ->height(84);

        $this->addMediaConversion('full')
            ->optimize()
            ->width(720)
            ->height(720);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_images')
            ->singleFile();;
    }

    protected $guarded = [];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function children(){
        return $this->hasMany(Category::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class);
    }
}
