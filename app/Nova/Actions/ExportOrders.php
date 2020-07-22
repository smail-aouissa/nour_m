<?php

namespace App\Nova\Actions;

use App\Models\Variation;
use Laravel\Nova\Actions\Action;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportOrders extends DownloadExcel implements WithMapping
{
    public function __construct()
    {

        $this->withFilename('commande-' . time() . '.xlsx');
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function map($order): array
    {
        $first_export = false;
        if( !$order->exported_at  ) $first_export = true;

        $order->exported_at = now();
        $order->save();

        if($first_export)
            $order->products->each(function ($p){
                if(intval($p->pivot->variation_id)){
                    Variation::where('id', $p->pivot->variation_id)->update([
                        'quantity'=> \DB::raw('quantity - ' . $p->pivot->quantity),
                    ]);
                }
            });

        return [
            $order->email,
            $order->name,
            optional($order->province)->code,
            optional($order->province)->name,
            optional($order->town)->name,
            $order->address,
            $order->phone,
            '',
            join(', ', $order->products->pluck('id')->toArray() ),
            $order->id,
            '',

            'Quantités: ' . join(', ', $order->products->map(function ($p){
                return $p->pivot->quantity ;
            })->toArray()),

            join(",\n ", $order->products->map(function ($p){
                $attributes = $p->pivot->attributes ? collect(json_decode($p->pivot->attributes)) : collect([]);
                $attr = '';
                if($attributes->has('color'))
                    $attr .= 'Couleur: '. $attributes->get('color') .', ';
                if($attributes->has('size'))
                    $attr .= 'Taille: '. $attributes->get('size');
                return $attr;
            })->toArray() ),
        ];
    }

    public function headings(): array
    {
        return [
            'email', 'nom', 'code_wilaya', 'wilaya', 'commune', 'adresse', 'telephone', 'telephone 2',
            'reference produit', 'sous reference 1', 'sous reference 2', 'quantite produit', 'remarque'
        ];
    }

    public function onSuccess(callable $callback)
    {

        return Action::message('Le fichier Excel a été exporté avec succes.');
    }

    public function name()
    {
        return 'Exporter Les commandes';
    }
}
