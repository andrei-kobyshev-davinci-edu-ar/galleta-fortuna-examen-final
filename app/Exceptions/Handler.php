<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        FraseDuplicadaException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->renderable(function (FraseDuplicadaException $e, $request) {
            return response()->json(['error' => 'Esta frase ya existe en la base de datos'], 409);
        });
    }
}