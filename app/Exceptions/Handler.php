<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Auth;


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

    public function render($request, Throwable $exception)
    {
        $largeException = parent::render($request, $exception);
        $statusCode = $largeException->getStatusCode();

        // Roll back the transaction
        DB::rollBack(DB::transactionLevel());

       

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['success' => FALSE, 'status' => STATUS_NOT_FOUND, 'message' => "NOT_FOUND"], STATUS_NOT_FOUND);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json(['success' => FALSE, 'status' => TOO_MANY_REQUESTS, 'message' => "TOO_MANY_ATTEMPTS"], TOO_MANY_REQUESTS);
        }

        if ($request->route() && $request->route()->getPrefix() == "api") {

            if ($exception instanceof AuthenticationException) {
                return response()->json(['success' => FALSE, 'status' => STATUS_UNAUTHORIZED, 'message' => "UNAUTHORIZED_ACCESS"], STATUS_UNAUTHORIZED);
            }

            $response = [];
            $response['message'] = 'An error has occurred. Please contact support for assistance. Error code: ' . $statusCode;
            $response['success'] = FALSE;
            if (config('app.debug')) {
                $response['trace'] = $exception->getMessage();
                $response['line'] = $exception->getLine();
                $response['file'] = $exception->getFile();
                $response['code'] = $exception->getCode();
            }


            return response()->json($response, $statusCode);
        }

        if ($exception instanceof AuthenticationException) {
            return redirect('/');
        }

      

        return parent::render($request, $exception);
    }
}
