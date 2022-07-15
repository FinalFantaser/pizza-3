<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request,  $exception)
    {
        if ($request->is('api/*') || $request->is('api')) {

            $request->headers->set('Accept', 'application/json');

            if ($exception instanceof \Exception) {
                // return response()->json(['response' => "Project id:{$request->project} Not Found'"], 404);
                return response()->json(['response' => $exception->getMessage()]);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Not Found'], 404);
            }

        }

        return parent::render($request, $exception);
    }
}
