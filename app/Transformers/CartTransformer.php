<?php

namespace App\Transformers;

use App\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
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
    public function transform(Cart $cart)
    {
        return [
            'identificador' => (int)$cart->products->id,
            'titulo' => (string)$cart->products->title,
            'detalles' => (string)$cart->products->description,
            'valor' => (float)$cart->products->price,          
            'disponibles' => (string)$cart->products->stock,
            'estado' => (string)$cart->products->status,
            'cantidad' => $cart->products->pluck('pivot.quantity'),
            'imagenes' => $cart->products->images->pluck('path'),
            'fechaCreacion' => (string)$cart->products->created_at,
            'fechaActualizacion' => (string)$cart->products->updated_at,
            'fechaEliminacion' => isset($cart->products->deleted_at) ? (string) $cart->products->deleted_at : null,
            'total'=>$cart->total,   
        ];
    }
}
