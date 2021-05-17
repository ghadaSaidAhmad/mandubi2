<?php

namespace App\Exceptions;

use App\Http\Traits\JsonResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use JsonResponse;
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
    protected $dontFlash = ['password', 'password_confirmation'];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
      //  return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Responsek
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof UnauthorizedException) {
            $this->initResponse('', $exception->getMessage(), 403, 'error');
            return response()->json($this->response, $this->code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $this->initResponse('', $exception->getMessage(), 404, 'error');
            return response()->json($this->response, $this->code);
        }

        if ($exception instanceof NotFoundHttpException) {
            $this->initResponse('', $exception->getMessage(), 404, 'error');
            return response()->json($this->response, $this->code);
        }

        if ($exception instanceof HttpResponseException) {
            $this->initResponse('', $exception->getMessage(), 442, 'error');
            return response()->json($this->response, $this->code);
        }

        if ($exception instanceof MobileVerificationException) {
            $this->initResponse('', $exception->getMessage(), 406, 'error');
            return response()->json($this->response, $this->code);
        }

        return parent::render($request, $exception);
    }
}
