<?php

namespace App\Lib;

class Response
{
    public $success = false;
    public $message = '';
    public $data = [];
    public $errors = [];

    // retornos http exitosos
    const HTTP_200_OK = 200;

    // retornos http redireccion
    const HTTP_300_MULTIPLE_CHOICE = 300;

    // retornos http error cliente
    const HTTP_400_BAD_REQUEST = 400;

    // retornos http error servidor
    const HTTP_500_INTERNAL_SERVER_ERROR = 500;

    public function getResponse()
    {
        if (!$this->success && empty($this->message)) {
            $this->message = 'Ocurrió un error inesperado';
        }

        return $this;
    }
}
