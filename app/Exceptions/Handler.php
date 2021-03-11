<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{

    private $code;
    private $msg;
    private $errorCode;

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
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BaseExceptions) {
            $this->code = $exception->code;
            $this->errorCode = $exception->errorCode;
            $this->msg = $exception->msg;
            $this->data = $exception->data;
        } else {
            if (config('app.debug')) {
                if (method_exists($exception, 'render') && $response = $exception->render($request)) {
                    return Router::toResponse($request, $response);
                } elseif ($exception instanceof Responsable) {
                    return $exception->toResponse($request);
                }
                $exception = $this->prepareException($exception);
                if ($exception instanceof HttpResponseException) {
                    return $exception->getResponse();
                } elseif ($exception instanceof AuthenticationException) {
                    return $this->unauthenticated($request, $exception);
                } elseif ($exception instanceof ValidationException) {
                    return $this->convertValidationExceptionToResponse($exception, $request);
                }
                return $this->prepareJsonResponse($request, $exception);
            } else {
                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->errorCode = 999;
//                $this->recordErrorLog($exception);
            }
        }
        $result = [
            'error_code' => $this->errorCode,
            'msg' => $this->msg,
            'data' => $this->data,
            'request_url' => $request->fullUrl()
        ];
        return response()->json($result, $this->code);
    }

    /*
     * 记录错误日志
     */
    private function recordErrorLog(Throwable $exception)
    {

    }
}
