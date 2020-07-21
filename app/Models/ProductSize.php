<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{

    protected $table = 'product_size';

    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
