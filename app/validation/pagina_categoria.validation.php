<?php

namespace App\Validation;

class PaginaCategoriaValidation
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
        // Valida: orden
        // =================================================
        $key = 'orden';

        if (!isset($data[$key])) {
            $errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (!is_numeric($value)) {
                if (empty($value)) {
                    $errors[$key][] = 'Este campo es obligatorio';
                } else {
                    $errors[$key][] = 'Debe ser un valor numérico';
                }
            } else {
                if ($value <= 0) {
                    $errors[$key][] = 'Debe ser mayor a cero';
                }
            }
        }

        // =================================================
        // Valida: activo
        // =================================================
        $key = 'activo';

        if (!isset($data[$key])) {
            $errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (!is_numeric($value)) {
                if (empty($value)) {
                    $errors[$key][] = 'Este campo es obligatorio';
                } else {
                    $errors[$key][] = 'Debe ser un valor numérico entre 0 y 1';
                }
            } else {
                if ($value != 0 && $value != 1) {
                    $errors[$key][] = 'Debe ser un valor numérico entre 0 y 1';
                }
            }
        }

        return $errors;
    }
}
