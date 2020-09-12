<?php

namespace App\Traits;

use App\Category;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait ApiResponser
{

    private function successResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function showMesagge($mesagge, $code = 200)
    {
        return $this->successResponse(['data' => $mesagge], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showOne(Model $instance, $model, $code = 200)
    {
        $transformer = $this->chooseTransformer($model);
        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse($instance, $code);
    }

    //automaticamente el metodo de transformer nos pone el data, por lo tanto no es necesario ponerlo en el return 
    protected function showDataPaginate($query, $model, $page = 5, $code = 200)
    {
        if (!isset($query)) {
            return $this->successResponse(['data' => $query], $code);
        }
        $transformer = $this->chooseTransformer($model);
        $query = $this->filterSortData($query, $transformer);
        $query = $query->paginate($page);
        $query = $this->transformData($query, $transformer);
        $query = $this->cacheResponse($query);

        return $this->successResponse($query, $code);
    }

    protected function showDataCollection($query, $model, $code = 200)
    {
        if (!isset($query)) {
            return $this->successResponse(['data' => $query], $code);
        }
        $transformer = $this->chooseTransformer($model);
        $query = $this->filterSortData($query, $transformer);
        $query = $query->get();
        $query = $this->transformData($query, $transformer);
        $query = $this->cacheResponse($query);

        return $this->successResponse($query, $code);
    }

    protected function chooseTransformer($model)
    {
        switch ($model) {
            case 'Product':
                return Product::transformer();
                break;
            case 'Category':
                return Category::transformer();
                   break;
            default:
        }
    }

    protected function filterSortData($query, $transformer)
    {
        $where = $this->filterData($transformer);

        if (empty($where) && !isset(request()->sort_by)) {
            return $query;
        }

        if (isset(request()->sort_by)) {
            $query = $this->sortData($query, $transformer);
        }

        if (!empty($where)) {
            $query = $query->where($where);
        }

        return $query;
    }

    protected function filterData($transformer)
    {
        $condition = array();
        foreach (request()->query() as $param => $value) {
            if ($param != 'sort_by') {

                $operator = $this->checkPriceOperator($param) ?? '=';
                $param = $operator != '=' ? 'valor' : $param;

                $attribute = $transformer::originalAttribute($param);

                if (isset($attribute, $value)) {
                    array_push($condition, [$attribute, $operator, $value]);
                }
            }
        }

        return $condition;
    }

    protected function checkPriceOperator($param)
    {
        if ($param == 'valor>') {
            return '>=';
        } else if ($param == 'valor<') {
            return '<=';
        }
        return null;
    }

    protected function sortData($query, $transformer)
    {
        $attribute = $transformer::originalAttribute(request()->sort_by);

        return $query->orderBy($attribute, 'asc');
    }


    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    protected function cacheResponse($data)
    {
        //obtenemos la URL actual
        $url = request()->url();
        //obtenemos todos los parametros en la URL
        $queryParams = request()->query();
        //ksort() este metodo de php ordena los parametros de un array dependiendo de la clave
        ksort($queryParams);

        //contruimos la query de parametros 
        $queryString = http_build_query($queryParams);

        //asignamos la url junto con los parametros que contruimos
        $fullUrl = "{$url}?{$queryString}";

        //el metodo remember() recibe como primer parametro la URL obtenida
        //segundo parametro, tiempo por el cual se mantendra el cache (el tiempo es el segundos)
        //tercer parametro un closure que se encargara de retornar los datos a realizar en cache 
        return Cache::remember($fullUrl, 60, function () use ($data) {
            return $data;
        });
    }
}
