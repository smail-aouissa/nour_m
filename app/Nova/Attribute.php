<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use R64\NovaFields\Row;

class Attribute extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Attribute::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'label',
    ];

    public static function availableForNavigation(Request $request)
    {
        return false;
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

            BelongsTo::make('Produit','product',Product::class)
                ->exceptOnForms(),

            Text::make('Label')
                ->sortable()
                ->rules('required', 'max:255'),

            Row::make('Variation', [
                \R64\NovaFields\Text::make('Label','label')
                    ->fieldClasses('w-full px-6 py-2')
                    ->rules('required', 'max:20')
                    ->hideLabelInForms(),
                \R64\NovaFields\Number::make('Quantité','quantity')
                    ->fieldClasses('w-full px-6 py-2')
                    ->rules('required', 'min:0')
                    ->hideLabelInForms(),
            ])->fieldClasses('w-full p-4')
                ->addRowText('Ajouter')
                ->hideFromIndex(),

            //HasMany::make('Vatitations','variations',Variation::class)
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
        return [];
    }
}
