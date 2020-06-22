<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;

class Color extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

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
        $this->addMediaCollection('color_images')
            ->singleFile();
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
