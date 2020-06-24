<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $guarded = [];

    public function province(){
        return $this->belongsTo(Province::class);
    }
}
