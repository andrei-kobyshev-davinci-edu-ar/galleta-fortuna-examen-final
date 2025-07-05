<?php

class FraseDuplicadaException extends Exception {
    public function __construct($message = "La frase ya existe en la base de datos", $code = 409) {
        parent::__construct($message, $code);
    }
}

class UsuarioDuplicadoException extends Exception {
    public function __construct($message = "El email ya está registrado", $code = 409) {
        parent::__construct($message, $code);
    }
}

class AccesoDenegadoException extends Exception {
    public function __construct($message = "Acceso denegado", $code = 403) {
        parent::__construct($message, $code);
    }
}