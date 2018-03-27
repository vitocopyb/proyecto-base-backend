<?php

use App\Lib\Response;
use App\Validation\UsuarioValidation;

$app->group('/usuarios', function () {

    // ===============================================================
    // crea un nuevo usuario
    // ===============================================================
    $this->post('', function ($req, $res, $args) {
        $response = new Response();
        $errors = UsuarioValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al crear un nuevo usuario';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien graba el nuevo registro
        $data = $this->model->usuario->crear($req->getParsedBody());

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al crear un nuevo usuario';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Usuario creado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // actualiza un usuario
    // ===============================================================
    $this->put('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $errors = UsuarioValidation::validate($req->getParsedBody(), true);

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al actualizar un usuario';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien actualiza el registro
        $data = $this->model->usuario->actualizar($req->getParsedBody(), $args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al actualizar un usuario';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Usuario actualizado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // elimina un usuario
    // ===============================================================
    $this->delete('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $data = $this->model->usuario->eliminar($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al eliminar un usuario';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Usuario eliminado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene los usuarios
    // ===============================================================
    $this->get('', function ($req, $res, $args) {
        $response = new Response();

        $limit = (int) $req->getQueryParam('limit');
        $page = (int) $req->getQueryParam('page');

        // setea valores
        $limit = ($limit <= 0) ? 10 : $limit;
        $page = ($page <= 0) ? 1 : $page;

        // calcula la cantidad de registros que debe saltarse para la paginacion
        $offset = ($page - 1) * $limit;

        $data = $this->model->usuario->listar($limit, $offset);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener los usuarios';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Usuarios obtenidas correctamente';
            $response->data = $data;
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene pagina por id
    // ===============================================================
    $this->get('/{id}', function ($req, $res, $args) {
        $response = new Response;
        $data = $this->model->usuario->obtener($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener el usuario';
            $response->errors = $data;
        } else {
            // si no encontro registros retorna bad request
            if (!$data['usuario']) {
                $codigo = $response::HTTP_400_BAD_REQUEST;
                $response->success = false;
                $response->message = 'Usuario no existe';
            } else {
                $codigo = $response::HTTP_200_OK;
                $response->success = true;
                $response->message = 'Usuario obtenido correctamente';
                $response->data = $data;
            }
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

//})->add( new AuthMiddleware($app) );
});
