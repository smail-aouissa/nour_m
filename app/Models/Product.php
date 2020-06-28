<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
    ];

    protected $appends = ['photos'];

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
        $this->addMediaCollection('product_images');
    }

    public function scopeWithRating(Builder $query)
    {
        $sub = Rating::query()
            ->selectRaw('AVG(ratings.rate)')
            ->where('ratings.product_id', \DB::raw('products.id'));

        $query->addSubSelect('rating', $sub);
    }

    public function orders(){
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity','price','attributes','product_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function colors(){
        return $this->hasMany(Color::class);
    }

    public function sizes(){
        return $this->hasMany(Size::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function getPhotosAttribute(){
        $photos = $this->hasMedia('product_images') ?
            $this->getMedia('product_images')->map(function ($item){
                return [
                    'thumb' => $item->getFullUrl('thumb'),
                    'full' => $item->getFullUrl('full'),
                ];
            }) : [];
        $this->unsetRelation('media');

        $this->colors->each(function ( $color )use ($photos){
            if($url = $color->photo)
                $photos->push([
                    'thumb' => null,
                    'full' => $url,
                ]);

        });

        return $photos;
    }

}
