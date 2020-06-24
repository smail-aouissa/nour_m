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
        return [
            $order->name,
            $order->phone,
            $order->email,
            //Date::dateTimeToExcel($user->created_at),
        ];
    }

    public function headings(): array
    {
        return [
            'name', 'phone', 'email'
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
