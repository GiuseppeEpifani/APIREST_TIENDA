<?php

namespace App\Http\Controllers\Panel\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CategoryRequest;

class CategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware('transform.input:' . Category::transformer())->only(['store','update']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Category::query();

        return $this->showDataCollection($query, 'Category');
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return $this->showMesagge('Categoria agregada.');
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return $this->showMesagge('Categoria actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showMesagge('Categoria eliminada.');
    }
}
