<?php

namespace App\Validation;

class UsuarioValidation
{
    public static function validate($data, $update = false)
    {
        // inicializa el objeto
        $errors = [];

        // =================================================
        // Valida: idRol
        // =================================================
        $key = 'idRol';

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
        // Valida: username
        // =================================================
        $key = 'username';

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
        // Valida: password
        // =================================================
        $key = 'password';

        if (!isset($data[$key])) {
            $errors[$key][] = 'Este campo es obligatorio';
        } else {
            // si esta creando el usuario entonces es obligatorio el password
            if(!$update) {
                if (empty($data[$key])) {
                    $errors[$key][] = 'Este campo es obligatorio';
                } else {
                    $value = $data[$key];
    
                    if (strlen($value) < 3) {
                        $errors[$key][] = 'Debe contener como mínimo 3 caracteres';
                    }
                }
            } else {
                if(!empty($data[$key])){
                    $value = $data[$key];
          
                    if(strlen($value) < 3) {
                      $response->errors[$key][] = 'Debe contener como mínimo 3 caracteres';
                    }
                  }
            }
        }

        // =================================================
        // Valida: email
        // =================================================
        $key = 'email';

        if (isset($data[$key])) {
            if (empty($data[$key])) {
                $errors[$key][] = 'Este campo es obligatorio';
            } else {
                $value = $data[$key];
                                
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$key][] = 'No es un correo válido';
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
