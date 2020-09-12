<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\ApiController;
use App\Services\CartService;


class CartController extends ApiController
{

    public $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showMesagge($this->cartService->countProducts());
    }

  
    public function showCart()
    {
      return  response()->json($this->cartService->showCartProducts());
    }

    
}
