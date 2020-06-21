<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Slider extends Model
{
    use HasMediaTrait;

    protected $guarded = [];

    //protected $appends = ['photo'];

    public function registerMediaConversions(Media $media = null)
    {

        $this->addMediaConversion('small')
            //->quality(100)
            ->devicePixelRatio(2)
            ->width(120)
            ->height(48);

        $this->addMediaConversion('full')
            ->width(1600)
            ->height(540);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('slider_images')->singleFile();
    }


    public function getPhotoAttribute(){
        return $this->getFirstMediaUrl('image');
    }
}
