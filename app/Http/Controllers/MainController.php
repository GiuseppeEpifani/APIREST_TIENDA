<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Product;


class MainController extends ApiController
{
    public function index()
    {  
        //\DB::connection()->enableQueryLog();

        $query = Product::query()->Available()->with('images','categories');
      
        return $this->showDataPaginate($query,'Product');
        //dd(\DB::getQueryLog());
        //return $this->showDataPaginate($query);;      
    }
}
