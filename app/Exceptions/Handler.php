<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function handleApiException($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return error('Entry for '.str_replace('App\\', '', $e->getModel()).' not found', 404);
        }

        if ($e instanceof ValidationException) {
            return validationError($e->validator);
        }

        if ($e instanceof AuthenticationException) {
            return error('Unauthenticated', 401);
        }

        if ($e instanceof AuthorizationException) {
            return error('Unauthorized', 403);
        }

        if ($e instanceof NotFoundHttpException) {
            return error('The specified URL cannot be found', 404);
        }

        if ($e instanceof SysException) {
            return error($e->getMessage(), $e->getCode());
        }

        return parent::render($request, $e);
    }
}
