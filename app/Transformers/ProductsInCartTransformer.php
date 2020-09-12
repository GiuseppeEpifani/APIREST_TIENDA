<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductsInCartTransformer extends TransformerAbstract
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
            'valorTotal' => isset($product->pivot->total) ? (float)$product->pivot->total : null,
            'imagenes' => $product->images->pluck('path')->first(),
            'cantidadEnCarrito' => isset($product->pivot->quantity) ? (int)$product->pivot->quantity : null,
        ];
    }
}
