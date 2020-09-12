<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
    ];

    //automaticamente laravel ve ha que modelo pertenece siempre y cuando mantengamos la consistencia de los nombres
    public function imageable()
    {
        return $this->morphTo();
    }
}
