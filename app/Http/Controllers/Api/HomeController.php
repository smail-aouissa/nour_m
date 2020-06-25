<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\TopPanel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        try {
            return response()->json([
                'topPanelItems' => TopPanel::select('text','link')
                    ->get(),
                'categories' => Category::select('label','id')
                    ->onlyParent()
                    ->with('children')
                    ->get(),
            ], 200);
        }catch(\Exception $e){
            return response()->noContent(500);
        }
    }

    public function index(){
        return response()->json([
            'sliders' => Slider::whereStatus(true)
                ->select('id','link','title','content')
                ->get(),

            'categories' => Category::select('label','id')
                ->onlyParent()
                ->with(['children'])
                ->limit(3)
                ->get()
                ->map
                ->append('photo'),

            'latestProducts' => Product::whereStatus(true)
                ->with('category:label,id','colors','sizes')
                ->limit(8)
                ->withRating()
                ->get(['products.promo_price AS offerPrice', 'products.*'])
                ->each
                ->setAppends(['photos']),

            'bestSellersProducts' => Product::whereStatus(true)
                ->with('category:label,id','colors','sizes')
                ->limit(4)
                ->withRating()
                ->orderBy('rating','desc')
                ->get(['products.promo_price AS offerPrice', 'products.*'])
                ->each
                ->setAppends(['photos']),

            'collections' => Collection::select('label','id')
                ->limit(4)
                ->get()
                ->each
                ->setAppends(['photo']),

            'testimonials' => Testimonial::all(),

        ], 200);
    }
}
