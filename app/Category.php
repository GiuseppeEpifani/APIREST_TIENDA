<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Product;
use App\Transformers\CategoryTransformer;

class Category extends Model
{
    use SoftDeletes;
 
    protected $fillable=[
        'name',
        'description',
    ];

    protected $hidden=[
        'pivot'
    ];
    
    protected $dates=['deleted_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
     }

     protected static function transformer()
    {
        return CategoryTransformer::class;
    }
}
