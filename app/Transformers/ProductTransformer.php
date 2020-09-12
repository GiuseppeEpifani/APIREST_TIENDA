<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identificador' => (int)$product->id,
            'titulo' => (string)$product->title,
            'detalles' => (string)$product->description,
            'valor' => (float)$product->price,          
            'disponibles' => (string)$product->stock,
            'estado' => (string)$product->status,
            'imagenes' => $product->images->pluck('path'),
            'categorias' => $product->categories->pluck('name')->unique()->values(),
            'fechaCreacion' => (string)$product->created_at,
            'fechaActualizacion' => (string)$product->updated_at,
            'fechaEliminacion' => isset($product->deleted_at) ? (string) $product->deleted_at : null, 
        ] ;
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'titulo' => 'title',
            'detalles' => 'description',
            'valor'=>'price',
            'disponibles' => 'stock',
            'estado' => 'status',
            'imagenes' => 'path', 
            'categorias' => 'categories' ,       
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'identificador',
            'title' => 'titulo',
            'description' => 'detalles',
            'price'=>'valor',
            'stock' => 'disponibles',
            'status' => 'estado',
            'path' => 'imagen',          
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
