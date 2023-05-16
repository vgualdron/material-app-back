<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;

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
        $this->renderable(function (\Throwable $e, $request) {
            if ($e instanceof AccessDeniedHttpException) {
                return response()->json([
                    'message' => ['No tienes autorización para realizar esta acción.']
                ], Response::HTTP_FORBIDDEN);
            }
        });
    }
}
