<?php

namespace App\Nova;

use App\Nova\Actions\ExportOrders;
// use App\Nova\Actions\InvalidateOrder;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    public static $group = "Commandes";

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name','phone','exported_at'
    ];


    public function authorizedToAdd(NovaRequest $request, $model)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdateForSerialization(Request $request)
    {
        return false;
    }

    public function authorizedToAttachAny(NovaRequest $request, $model)
    {
        return false;
    }

    public static function label()
    {
        return 'Commande Exportée';
    }

    /**
     * @param NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereNotNull('exported_at');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            DateTime::make('Exporté le','exported_at')
                // ->format('DD-MMM-YYYY HH:mm')
                ->sortable(),

            Text::make('Nom du client','name')
                ->sortable(),

            Text::make('N° Téléphone','phone'),

            Text::make('Email','email')
                ->onlyOnDetail(),

            Text::make('Adresse','address')
                ->onlyOnDetail(),

            BelongsTo::make('Wilaya','province',Province::class),

            BelongsTo::make('Ville','town',Town::class)
                ->onlyOnDetail(),

            Text::make('Nbr du produits',function (){
                return $this->products->count();
            })->onlyOnDetail(),

            DateTime::make('Crée le','created_at')->format('DD-MMM-YYYY HH:mm'),

            BelongsToMany::make('Produits','products',Product::class)
            
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            // new InvalidateOrder,
            new ExportOrders
        ];
    }

}
