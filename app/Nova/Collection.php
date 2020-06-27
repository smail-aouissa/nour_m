<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Collection extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Collection::class;

    public static $group = "Objets";

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->limit(4);
    }

    public function authorizedToAdd(NovaRequest $request, $model)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public static function label()
    {
        return 'Collections';
    }

    public $resolution = [
        '1' => ['width' => 359 , 'height' => 430],
        '2' => ['width' => 359 , 'height' => 220],
        '3' => ['width' => 359 , 'height' => 220],
        '4' => ['width' => 750 , 'height' => 150],
    ];

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


            Text::make('Label')
                ->sortable()
                ->rules('required', 'max:255'),

            Images::make('Images','collection_images')
                ->conversionOnIndexView('full')
                ->conversionOnDetailView('full')
                ->conversionOnForm('full')
                ->showDimensions()
                ->singleImageRules('dimensions:width='.$this->resolution[$this->id ?? 1]['width'].
                    ',height='.$this->resolution[$this->id ?? 1]['height'])
                ->required()
                ->help(
                    'Résolution d\'images est: '. $this->resolution[$this->id ?? 1]['width'] .
                    'px X '.$this->resolution[$this->id ?? 1]['height'].'px'
                ),

            BelongsToMany::make('Produits', 'products', Product::class)
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
