<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Prophecy\Exception\Doubler\MethodNotFoundException;
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
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * By default, the report method passes the exception to the base class where the exception is logged. However, you are free to log exceptions however you wish.
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }
    
    public function render($request, Throwable $exception)
    {
        /**
         * these Exception will only run for Api .. when header of ['Accept' : 'application/json'] is present and for web it will return laravel default validations
         */
        if ($request->wantsJson()) {
            if($exception instanceOf ModelNotFoundException){
                $modelName = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("The record you are trying to access does not exit or it was deleted for {$modelName}",404);
            }elseif($exception instanceOf NotFoundHttpException){
                return $this->errorResponse('Page Not Found ',404);
            }elseif($exception instanceOf AuthenticationException){
                return $this->errorResponse('Unauthenticated please login',401);
            }elseif($exception instanceOf AuthorizationException){
                return $this->errorResponse('Sorry you are Unauthorized to perfom this action',403);
            }elseif($exception instanceOf HttpException){
                return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
            }elseif($exception instanceOf MethodNotAllowedHttpException){
                return $this->errorResponse($exception->getMessage(),405);
            }elseif($exception instanceOf QueryException){
                $errorCode = $exception->errorInfo[1];
                if ($errorCode == 1451) {
                    return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource',409); //409  is conflict error
                }
            }
            
            
        }
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        else{
            return $this->errorResponse('Unexpected Exception',500);  //generic Unexpected exception response
        }
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        

        
    }
}
