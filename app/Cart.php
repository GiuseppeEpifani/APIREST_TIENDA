<?php

namespace App;

use App\Product;
use App\Transformers\CartTransformer;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $transformer=CartTransformer::class;
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')->withPivot('quantity','total');
    }

    public function getTotalAttribute()
    {
        return $this->products->pluck('total')->sum();
    }
}
