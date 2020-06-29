<?php

namespace App\Nova\Actions;

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
        return $order->products->map(function ($p) use ($order){
            $attr = '';
            if($p->color)
                $attr .= 'Couleur: '. $p->color->label .', ';
            if($p->size)
                $attr .= 'Taille: '. $p->size->label;

            return [
                $order->email,
                $order->name,
                optional($order->province)->code,
                optional($order->province)->name,
                optional($order->town)->name,
                $order->address,
                $order->phone,
                '',
                $p->id,
                $order->id,
                '',
                $p->pivot->quantity,
                $attr,
            ];
        })->toArray();
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
