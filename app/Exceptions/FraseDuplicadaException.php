<?php

namespace App\Exceptions;

use Exception;

class FraseDuplicadaException extends Exception {
    public function __construct($message = "La frase ya existe en la base de datos", $code = 409) {
        parent::__construct($message, $code);
    }
}