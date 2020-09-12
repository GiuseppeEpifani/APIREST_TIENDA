<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->errorResponse("Faltaron campos por completar", 422);
        }

        //exception en caso de que se haga get a un id inexistente
        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$this->transformerModel($model)} con el id especificado", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse('No autenticado.', 401);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse("No posee permisos para ejecutar esta acción", 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("No se encontro la URL especificada.", 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El metodo especificado en la peticion no es valido.", 405);
        }

        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];
            if ($codigo == 1451) {
                return $this->errorResponse("No se puede eliminar de forma permanente el recurso porque esta realcionado con algun otro.", 409);
            }
        }

        //con esto controlamos cualquier otro tipo de exception http generado
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }


        //aca accedemos al archivo config, para ver si nuestra app esta en produccion o desarrollo, de esta forma controlamos los errores 500, solo en produccion.
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Falla inesperada, Intente luego.", 500);
    }

    protected function transformerModel($model)
    {
        switch ($model) {
            case 'product':
                return 'producto';
                break;
            case 'cart':
                return 'carrito';
                break;
            case 'image':
                return 'imagen';
                break;
            case 'payment':
                return 'pago';
                break;
            case 'order':
                return 'orden';
                break;
            case 'user':
                return 'usuario';
                break;
            default:
            return '';
        }
    }
}
