<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{

    protected $guarded = [];

    public function color(){
        return $this->belongsTo(ColorProduct::class,'color_product_id');
    }

    public function size(){
        return $this->belongsTo(ProductSize::class,'product_size_id');
    }
}
