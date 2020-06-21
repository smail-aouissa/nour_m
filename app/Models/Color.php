<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Color extends Model
{
    use HasMediaTrait;

    protected $guarded = [];
}
