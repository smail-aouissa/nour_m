<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Province;


class ProvinceController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'provinces' => Province::all()
        ],200);
    }

    public function show($id){
        return response()->json([
            'towns' => Province::findOrFail($id)->towns
        ],200);
    }
}
