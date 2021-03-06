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

    public $attr;

    protected $appends = ['photos'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Product $model){
            $attributes = json_decode($model->attr, true);

            if($model->colors()->count() > 0){
                $model_colors = $model->colors;
                $deleted_colors = $model_colors->whereNotIn('label',collect($attributes['colors'])->pluck('label'))->pluck('id');
                $model->colors()->whereIn('id',$deleted_colors)->delete();
            }

            if($model->sizes()->count() > 0){
                $model_sizes = $model->sizes;
                $deleted_sizes = $model_sizes->whereNotIn('label',collect($attributes['sizes'])->pluck('label'))->pluck('id');
                $model->sizes()->whereIn('id',$deleted_sizes)->delete();
            }

            if( count($attributes['colors']) === 0 && count($attributes['sizes']) === 0 ){
                $model->variations()->delete();
                return;
            }

            $colors = collect($attributes['colors'])->map(function ($color) use ($model) {
                return $model->colors()->updateOrCreate([
                    'label' => $color['label'],
                    "product_id" => $model->id,
                    'code' => $color['code'],
                ], [
                    "product_id" => $model->id,
                    'label' => $color['label'],
                    'code' => $color['code'],
                ]);
            });

            $sizes = collect($attributes['sizes'])->map(function ($size) use ($model) {
                return $model->sizes()->updateOrCreate([
                    "product_id" => $model->id,
                    'label' => $size['label'],
                ], [
                    "product_id" => $model->id,
                    'label' => $size['label'],
                ]);
            });

            $variations = cartesian([ "color" => $colors->toArray() , 'size' => $sizes->toArray() ]);

            $to_keep = collect([]);

            foreach ($variations as $row){
                 $keep = $model->variations()
                    ->firstOrCreate([
                        'product_id' => $model->id,
                        'color_product_id' => array_key_exists('color',$row) ? $row['color']['id'] : null,
                        'product_size_id' => array_key_exists('size',$row) ? $row['size']['id'] : null,
                    ]);

                $to_keep->add($keep);
            }

            $model->variations()->whereNotIn('id',$to_keep->pluck('id')->toArray())->delete();

        });
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
        return $this->hasMany(ColorProduct::class);
    }

    public function sizes(){
        return $this->hasMany(ProductSize::class);
    }

    public function variations(){
        return $this->hasMany(Variation::class);
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
            }) : collect([]);
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
