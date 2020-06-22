<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;

class Slider extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    //protected $appends = ['photo'];

    public function registerMediaConversions(Media $media = null): void
    {

        $this->addMediaConversion('thumb')
            ->devicePixelRatio(2)
            ->width(120)
            ->height(48);

        $this->addMediaConversion('full')
            ->nonOptimized()
            ->width(1600)
            ->height(540);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('slider_images')
            ->useDisk('sliders')
            ->singleFile();
    }


    public function getPhotoAttribute(){
        return $this->getFirstMediaUrl('image');
    }
}
