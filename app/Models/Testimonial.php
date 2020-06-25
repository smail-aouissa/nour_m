<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $appends = ['photo'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->height(80)
            ->width(80)
            ->optimize();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('testimonial_images')
            ->singleFile();
    }

    public function getPhotoAttribute(){
        $result = $this->getFirstMediaUrl('testimonial_images','thumb');
        $this->unsetRelation('media');
        return $result;
    }
}
