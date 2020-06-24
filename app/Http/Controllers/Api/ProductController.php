<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id){

        return response()->json([
            'product' => Product::query()
                ->select(['products.promo_price AS offerPrice', 'products.*'])
                ->with('category:label,id','colors','sizes')
                ->withRating()
                ->findOrFail($id),

            'relatedProducts' => Product::findOrFail($id)
                ->category
                ->products()
                ->with('category:label,id','colors','sizes')
                ->withRating()
                ->limit(4)
                ->get()
        ],200);
    }
}
