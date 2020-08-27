<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Variation;
use App\Models\Order;
use App\Models\Size;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderRequest $request){

        $details = $request->get('details');

        DB::beginTransaction();

        $order = Order::create([
            'name' => $details['fullName'],
            'address' => $details['address'],
            'email' => $details['email'],
            'phone' => $details['phone'],
            'province_id' => $details['province']['id'] ?? null,
            'town_id' => $details['town'] ? $details['town']['id'] : null,
        ]);

        $products = collect($request->items)->map(function ($item){
            $attributes = [];
            if($item['color']) $attributes['color'] = $item['color'] ? $item['color']['code'] : null;
            if($item['size']) $attributes['size'] = $item['size'] ? $item['size']['label'] : null;
            return [
                'product_id' => $item['id'],
                'variation_id' => $item['variation']['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'attributes' => json_encode($attributes),
            ];
            $variation=Variation::find($item['variation']['id']);
            $variation->quantity=$variation->quantity-$item['quantity'];
            $variation->save();
        });

        $order->products()->sync( $products );
        DB::commit();

        return response()->noContent(200);
    }
}
