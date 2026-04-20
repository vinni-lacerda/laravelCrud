<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected array $dontReport = [
        ApiException::class,
    ];

    public function register(): void
    {
        $this->renderable(function (ValidationException $exception, $request): JsonResponse {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Falha de validação.',
                    'errors' => $exception->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return parent::render($request, $exception);
        });

        $this->renderable(function (ModelNotFoundException $exception, $request): JsonResponse {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Registro não encontrado.',
                ], Response::HTTP_NOT_FOUND);
            }

            return parent::render($request, $exception);
        });

        $this->renderable(function (ApiException $exception, $request): JsonResponse {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    'code' => $exception->errorCode(),
                ], $exception->status());
            }

            return parent::render($request, $exception);
        });
    }
}
