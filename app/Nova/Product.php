<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laouis\AttributesField\AttributesField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

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

    protected function authorizedToUpdateForSerialization(NovaRequest $request)
    {
        return true;
    }

    public static function label()
    {
        return "Produits";
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

            BelongsTo::make('Catégorie','category',Category::class)
                ->hideFromIndex(function ($request){
                    return $request->request->get('relationshipType');
                }),

            Text::make('Label')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make('Tri derniere produit','sort_new_products')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Tri derniere tendance','sort_last_trend')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Prix','price')
                ->default(function () { return 1.00; })
                ->sortable()
                ->hideFromIndex()
                // ->hideFromIndex(function ($request){
                //     return $request->request->get('relationshipType');
                // })
                ->rules('required', 'numeric','min:0'),

            Text::make('Prix promo','promo_price')
                ->default(function () { return 0.00; })
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'numeric','min:0'),

            Textarea::make('Déscription','description')
                ->onlyOnDetail()
                ->rules('nullable', 'string'),

            Trix::make('Déscription','description')
                ->onlyOnForms()
                ->rules('required'),

            AttributesField::make('Attributs','attr')
                ->withMeta([
                    'colors' => \App\Models\Color::all(),
                    'sizes' => \App\Models\Size::all(),
                    'selectedColors' => $this->colors,
                    'selectedSizes' => $this->sizes,
                ]),

            Boolean::make('Statut','status')->default(function (){
                    return true;
                })->hideFromIndex(function ($request){
                    return $request->request->get('relationshipType');
                }),

            Images::make('Images','product_images')
                ->conversionOnIndexView('thumb')
                ->conversionOnDetailView('full')
                ->conversionOnForm('full')
                ->hideFromIndex(),

            HasMany::make('Couleurs','colors',ColorProduct::class),

            //HasMany::make('Tailles','sizes',Size::class),

            BelongsToMany::make('Commandes','orders',Order::class)
                ->fields(function (){
                    return [
                        Text::make('Prix de vente','price'),
                        Text::make('quantity','quantity'),
                        Text::make('Attributes',function ($model){
                            $attributes = json_decode($model->attributes,true) ?? [];
                            $result = '';
                            if(array_key_exists('color',$attributes))
                                $result .= "<div class='flex items-center mt-1'>Couleur: <div class='mx-2' style='background-color: ".$attributes['color']."; border-radius: 30px;width: 15px;height: 15px'></div></div>";
                            if(array_key_exists('size', $attributes))
                                $result .= "<div>Taille: <span>".$attributes['size']."</span></div>";
                            return count($attributes) > 0 ? $result : 'Non défini';
                        })->asHtml(),
                    ];
                })->onlyOnIndex(),

            BelongsToMany::make('Commandes','orders',ExportOrder::class)
                ->fields(function (){
                    return [
                        Text::make('Prix de vente','price'),
                        Text::make('quantity','quantity'),
                        Text::make('Attributes',function ($model){
                            $attributes = json_decode($model->attributes,true) ?? [];
                            $result = '';
                            if(array_key_exists('color',$attributes))
                                $result .= "<div class='flex items-center mt-1'>Couleur: <div class='mx-2' style='background-color: ".$attributes['color']."; border-radius: 30px;width: 15px;height: 15px'></div></div>";
                            if(array_key_exists('size', $attributes))
                                $result .= "<div>Taille: <span>".$attributes['size']."</span></div>";
                            return count($attributes) > 0 ? $result : 'Non défini';
                        })->asHtml(),
                    ];
                })->onlyOnIndex(),

            HasMany::make('Attributes','variations',Variation::class),
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
