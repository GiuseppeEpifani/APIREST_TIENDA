<?php

namespace App;

use App\Cart;
use App\Image;
use App\Order;
use App\Scopes\ProductAvailableScope;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;   
    //sire para cuando se hagan herencias a otros modelos, estos tomen la tabla de products
    protected $table = 'products';
    protected $dates= ['deleted_at'];

 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'status',
    ];

      /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
       // static::addGlobalScope(new ProductAvailableScope);
    }

    public function carts()
    {
        return $this->morphedByMany(Cart::class, 'productable')->withPivot('quantity','total');
    }

    public function orders()
    {
        //pertenece
        return $this->morphedByMany(Order::class, 'productable')->withPivot('quantity','total');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');

    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    //scoped estos sirven para hacer filtros con where
    public function scopeAvailable($query)
    {
        $query->where('status', 'available');
    }

    //geter
    public function getTotalAttribute()
    {
        return $this->pivot->quantity * $this->price;
    }

    protected static function transformer()
    {
        return ProductTransformer::class;
    }
}
