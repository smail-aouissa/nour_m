<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Models\Category;
use App\Models\Color;
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
            if($item['color']) $attributes['color'] = $item['color']['code'];
            if($item['size']) $attributes['size'] = $item['size']['label'];
            return [
                'product_id' => $item['id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'attributes' => json_encode($attributes)
            ];
        });

        $order->products()->sync( $products );

        DB::commit();

        return response()->noContent(200);
    }

    public function sstore(OrderRequest $request){

        $details = $request->get('details');

        dump($details);

        try {
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
                if($item['color']){
                    array_merge($attributes , [ 'color' => $item['color']['code'] ]);
                }
                if($item['size']){
                    array_merge($attributes , [ 'size' => $item['size']['label'] ]);
                }
                dump([
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    //'attributes' => $attributes
                ]);
                return [
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    //'attributes' => $attributes
                ];
            });

            dump($products);

            $order->products()->sync( $products );

            DB::commit();

            return response()->noContent(200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->noContent(500);
        }
    }
}
