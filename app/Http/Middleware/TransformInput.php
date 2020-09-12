<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {  
        $transformedInput = [];
        
        if($request->file('imagenes')){
            $transformedInput[$transformer::originalAttribute('imagenes')] = $request->file('imagenes');
        }
        //recorremos los campos recibidos unicamente en el cuerpo de la consulta y no como los parametros
        //esto lo realizamos con request->all() , que es lo que tiene relacionado directamente con la peticion y no con la URL y con los parametros de consulta
        foreach ($request->request->all() as $input => $value) {
            //si el valor recibido coincide con una clave de originalAtribute() retornara el key original
            //luego asignamos el valor al nuevo array
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        //reemplazamos el array de la peticion actual por el uno nuevo $transformedInput[]
       $request->replace($transformedInput);
        
        return $next($request);
    }
}
