<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'float'
    ];

    public function towns(){
        return $this->hasMany(Town::class);
    }
}
