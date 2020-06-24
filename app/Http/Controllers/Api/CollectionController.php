<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function __invoke(){
        // TODO colors of collection
        return response()->json([
            'sections' => Collection::all(['id','label']),
            'colors' => Color::groupBy(['code'])->get(['id','code','label']),
            'sizes' => Size::groupBy(['label'])->get(['id','label']),
        ],200);
    }

    public function show(Request $request, $id){
        $filters = $request->all();
        $collection = Collection::query()->findOrFail($id);

        $price = array_key_exists('price',$filters) && count($filters['price']) > 0;
        $sizes = array_key_exists('sizes',$filters) && count($filters['sizes']);
        $colors = array_key_exists('colors',$filters) && count($filters['colors']);

        return response()->json([
            'products' => $collection->products()
                ->when( $price , function (Builder $q) use($filters) {
                    $q->where([
                        ['price', '>=' , $filters['price']['min']],
                        ['price', '<=' , $filters['price']['max']],
                    ]);
                })
                ->when( $colors , function (Builder $q) use($filters) {
                    $q->whereHas('colors', function (Builder $q) use($filters) {
                        $q->whereIn('code', collect($filters['colors'])->pluck('code')->toArray() );
                    });
                })
                ->when( $sizes , function (Builder $q) use($filters) {
                    $q->whereHas('sizes', function (Builder $q) use($filters) {
                        $q->whereIn('label', collect($filters['sizes'])->pluck('label')->toArray() );
                    });
                })
                ->select('products.promo_price AS offerPrice', 'products.*')
                ->with('category:label,id','colors','sizes')
                ->limit(4)
                ->withRating()
                ->orderBy('rating','desc')
                ->paginate(8)

        ],200);
    }
}
