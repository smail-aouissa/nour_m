<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'validated_at' => 'datetime',
        'exported_at' => 'datetime'
    ];

    public function products(){
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity','price','attributes','product_id')
            ->withTimestamps();
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function town(){
        return $this->belongsTo(Town::class);
    }
}
