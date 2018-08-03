<?php

namespace App\Exceptions;

use Encore\Admin\Reporter\Reporter;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            Reporter::report($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {
            $response = [];
            $error = $this->convertExceptionToResponse($exception);
            $response['status'] = false;
            $response['message'] = 'something error';
            if (config('app.debug')) {
                $response['message'] = empty($exception->getMessage()) ? 'something error' : $exception->getMessage();
                if ($error->getStatusCode() >= 500) {
                    if (config('app.debug')) {
                        $response['data'] = $exception->getTraceAsString();
                        $response['code'] = $exception->getCode();
                    }
                }
            }
            return response()->json($response, $error->getStatusCode());
        } else {
            return parent::render($request, $exception);
        }
    }
}
