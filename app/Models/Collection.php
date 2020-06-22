<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
    }

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
        $this->addMediaCollection('collection_images')
            ->singleFile();
    }

    public function products(){
        return $this->belongsToMany(Product::class,'collection_product');
    }

    public function getPhotoAttribute(){
        $result = $this->getFirstMediaUrl('collection_images');
        $this->unsetRelation('media');
        return $result;
    }
}
