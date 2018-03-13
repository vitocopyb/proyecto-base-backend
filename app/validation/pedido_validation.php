<?php
namespace App\Validation;

use App\Lib\Response;

class PedidoValidation {
    public static function validate($data) {
        $response = new Response();

        $key = 'Cliente';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if(strlen($value) < 10) {
              $response->errors[$key][] = 'Debe contener como mínimo 10 caracteres';
            }
        }

        $key = 'Empleado_id';
        $value = $data[$key];
        if(!is_numeric($value)) {
          if(empty($value)){
            $response->errors[$key][] = 'Este campo es obligatorio';
          } else {
            $response->errors[$key][] = 'El valor ingresado no es un id válido';
          }
        } else {
            if($value <= 0) {
              $response->errors[$key][] = 'El valor ingresado debe ser mayor a cero';
            }
        }

        $key = 'Total';
        $value = $data[$key];
        if(!is_numeric($value)) {
          if(empty($value)){
            $response->errors[$key][] = 'Este campo es obligatorio';
          } else {
            $response->errors[$key][] = 'El valor ingresado no es un valor válido';
          }
        } else {
            if($value <= 0) {
              $response->errors[$key][] = 'El valor ingresado debe ser mayor a cero';
            }
        }

        $key = 'Detalle';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } elseif (!is_array($data[$key])) {
            $response->errors[$key][] = 'Detalle no válido';
        } else {
            $value = $data[$key];

            if(count($value) === 0) {
              $response->errors[$key][] = 'Debe ingresar un detalle para el pedido';
            }
        }
        
        $response->setResponse(count($response->errors) === 0);

        return $response;
    }
}
