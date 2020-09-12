<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Cart;
use App\Product;
use App\Services\CartService;


class ProductCartController extends ApiController
{
    public $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {    
        $cart = $this->cartService->getFromSessionOrCreate();
     
        $cartPivot = $cart->products()
            ->find($product->id)
            ->pivot ?? null;

        $quantity = $cartPivot->quantity ?? 0;

        $total = $cartPivot->total ?? 0;

        $price = $product->price ?? 0;

        if ($product->stock < $quantity + 1) {
            return $this->errorResponse('Tu carrito excede el stock disponible del producto seleccionado', 422);
        }

        $cart->products()->syncWithoutDetaching([
            $product->id => ['quantity' => $quantity + 1, 'total' => $total + $price],
        ]);
  
        return $this->showMesagge('Producto ' . $product->title . ' agregado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product, Cart $cart)
    {
        return $this->showMesagge($this->cartService->discountProducts($product, $cart));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Cart $cart)
    {
        $cart->products()->detach($product->id);

        return $this->showMesagge('Producto ' . $product->title . ' eliminado');
    }
}
