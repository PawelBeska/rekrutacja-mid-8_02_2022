<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $e): Response|JsonResponse|Redirector|RedirectResponse|Application|ResponseAlias
    {
        if ($e instanceof ModelNotFoundException)
            return response()->json([
                'status' => "error",
                'code' => "404",
                'message' => "Object not found",

            ], ResponseAlias::HTTP_NOT_FOUND);

        if ($e instanceof NotFoundHttpException)
            if ($request->wantsJson())
                return response()->json([
                    'status' => "error",
                    'code' => "404",
                    'message' => "Page not found",

                ], ResponseAlias::HTTP_NOT_FOUND);

        if ($e instanceof AuthorizationException) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'status' => "error",
                        'code' => "403",
                        'message' => 'Unauthorized.'
                    ], 403);
            }
        }

        return parent::render($request, $e);
    }
}
