<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // You can optionally log the exceptions or perform other actions here
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions by providing JSON responses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    protected function handleApiException(Request $request, Throwable $e)
    {
        $exception = $this->prepareException($e);

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * Create a custom API response based on the exception type and the status code.
     *
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    protected function customApiResponse(Throwable $exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [
            'message' => $exception->getMessage()
        ];

        switch ($statusCode) {
            case 404:
                $response['message'] = 'Resource not found';
                break;
            case 403:
                $response['message'] = 'Access denied';
                break;
            case 401:
                $response['message'] = 'Authentication required';
                break;
            case 419:
                $response['message'] = 'Page expired';
                break;
            default:
                $response['message'] = $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        return response()->json($response, $statusCode);
    }
}
