<?php

namespace App\Http\Controllers\Panel\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ProductRequest;
use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends ApiController
{

    public function __construct()
    {
        $this->middleware('transform.input:' . Product::transformer())->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Product::query()->with('images', 'categories');

        return $this->showDataPaginate($query, 'Product');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //el validated() sirve para agregar masivamente solo los datos que fueron validados, independiente de que en el fillable hayan mas
        $product = Product::create($request->validated());

        foreach ($request->path as $path) {
            $image = new Image;
            $image->path = $path->store('');
            $product->images()->save($image);
        }

        $product->categories()->attach($request->categories);

        return $this->showMesagge('Producto agregado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->has('path')) {
            foreach ($product->images as $pathDelete) {
                Storage::delete($pathDelete->path);
            }
            $product->images()->delete();

            foreach ($request->path as $path) {
                $image = new Image;
                $image->path = $path->store('');
                $product->images()->save($image);
            }
        }

        if($request->has('categories')){
            $product->categories()->detach();
            $product->categories()->attach($request->categories);
        }
    
        return $this->showMesagge('Producto actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->showMesagge('Producto eliminado.');
    }
}
