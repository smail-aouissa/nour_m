<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function variations(){
        return $this->hasMany(Variation::class);
    }

    public function setVariationAttribute($value){
        if(is_array($value) && count($value)){
            collect($value)->each(function ($item){
                $this->variations()->updateOrCreate([
                    'id' => $item['id'] ?? null ,
                ],collect($item)->except('row_id')->toArray() );
            });
        }
    }

    public function getVariationAttribute(){
        return $this->variations;
    }
}
