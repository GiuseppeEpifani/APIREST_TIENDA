<?php

namespace App\Services;

use App\Cart;
use App\Product;
use App\Traits\ApiResponser;
use App\Transformers\ProductsInCartTransformer;

class CartService
{

    use ApiResponser;

    public function getFromSession()
    {
        $cartId = session('cart');
        $cart = Cart::find($cartId);

        return $cart;
    }

    public function getFromSessionOrCreate()
    {
        $cartRecive = $this->getFromSession();

        $cart = $cartRecive ?? Cart::create();
        session(['cart' => $cart->id]);

        return $cart;
    }

    public function showCartProducts()
    {
        $cart = $this->getFromSessionOrCreate();

        $products = $cart->products;

        $transformer = ProductsInCartTransformer::class;
        $products = $this->transformData($products, $transformer);
    
        return  $products;
    }

    public function countProducts()
    {
        $cart = $this->getFromSession();

        if ($cart != null) {
            return $cart->products->pluck('pivot.quantity')->sum();
        }

        return 0;
    }

    public function discountProducts(Product $product, Cart $cart)
    {
        if ($cart != null) {
            $productInCart = $cart->products()->select('quantity', 'total')->find($product->id) ?? null;

            $stock = $productInCart->pivot->quantity ?? 0;
            $total = $productInCart->pivot->total ?? 0;
            $price = $product->price;

            if ($stock > 0) {
                $stock = $productInCart->pivot->quantity = $stock - 1;
                $total = $productInCart->pivot->total = $total - $price;
                $productInCart->pivot->save();
                
                return  $stock;
            }
        }

        return 0;
    }
}
