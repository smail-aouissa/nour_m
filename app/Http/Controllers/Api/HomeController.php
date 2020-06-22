<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\TopPanel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'topPanelItems' => TopPanel::select('text','link')->get(),
            'categories' => Category::select('label','id')->with('children')->get(),
        ], 200);
    }

    public function index(){
        return response()->json([
            'sliders' => Slider::whereStatus(true)
                ->select('id','link','title','content')
                ->get(),

            'categories' => Category::select('label','id')
                ->with(['children'])
                ->limit(3)
                ->get()
                ->forget('media')
                ->map(function ($category){
                    return $category->append('photo');
                }),
        ], 200);
    }
}
