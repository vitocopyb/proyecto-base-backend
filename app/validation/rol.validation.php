<?php

namespace App\Validation;

class RolValidation
{
    public static function validate($data)
    {
        // inicializa el objeto
        $errors = [];

        // =================================================
        // Valida: nombre
        // =================================================
        $key = 'nombre';

        if (!isset($data[$key])) {
            $errors[$key][] = 'Este campo es obligatorio';
        } else {
            if (empty($data[$key])) {
                $errors[$key][] = 'Este campo es obligatorio';
            } else {
                $value = $data[$key];

                if (strlen($value) < 3) {
                    $errors[$key][] = 'Debe contener como mínimo 3 caracteres';
                }
            }
        }

        // =================================================
        // Valida: llave
        // =================================================
        $key = 'llave';

        if (!isset($data[$key])) {
            $errors[$key][] = 'Este campo es obligatorio';
        } else {
            if (empty($data[$key])) {
                $errors[$key][] = 'Este campo es obligatorio';
            } else {
                $value = $data[$key];

                if (strlen($value) < 3) {
                    $errors[$key][] = 'Debe contener como mínimo 3 caracteres';
                }
            }
        }

        return $errors;
    }
}
