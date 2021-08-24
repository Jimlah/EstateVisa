<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json(['status'=> 'error','message' => "Object not found"], 404);
        });

        $this->renderable(function (BindingResolutionException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return response()->json(['status'=>'warning','message' => $e->getMessage()], 403);
        });
    }
}