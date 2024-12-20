<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

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

        $this->renderable(function (ValidationException $exception, $request) {
            if (! $request->wantsJson()) {
                return null; // Laravel handles as usual
            }

            throw CustomValidationException::withMessages(
                $exception->validator->getMessageBag()->getMessages()
            );
        });
        $this->renderable(function (InvalidOrderException $e, Request $request) {
            return response()->view('errors.invalid-order', [], 500);
        });
        $this->renderable(function (ModelNotFoundException $e, Request $request) {

            return response()->json([
                'status' => false,
                'message' => 'Record not found.',
                'code' => 404,
            ], 404);

        });
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Resource not found.',
                    'code' => 404,
                ], 404);
            }
        });
        $this->renderable(function (GeneralJsonException $e) {
            if (! request()->wantsJson()) {
                return null; // Laravel handles as usual
            }

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], $e->getCode());
        });

        //  $this->reportable(function (Throwable $e) {
        //    //
        // });
    }
}
