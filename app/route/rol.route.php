<?php

use App\Lib\Response;
use App\Validation\RolValidation;

$app->group('/roles', function () {
    
    // ===============================================================
    // crea un nuevo rol
    // ===============================================================
    $this->post('', function ($req, $res, $args) {
        $response = new Response();
        $errors = RolValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al crear un nuevo rol';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien graba el nuevo registro
        $data = $this->model->rol->crear($req->getParsedBody());

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al crear un nuevo rol';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Rol creado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // actualiza un rol
    // ===============================================================
    $this->put('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $errors = RolValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al actualizar un rol';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien actualiza el registro
        $data = $this->model->rol->actualizar($req->getParsedBody(), $args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al actualizar un rol';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Rol actualizado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // elimina un rol
    // ===============================================================
    $this->delete('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $data = $this->model->rol->eliminar($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al eliminar un rol';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Rol eliminado correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene los roles
    // ===============================================================
    $this->get('', function ($req, $res, $args) {
        $response = new Response();

        // $limit = (int)$req->getQueryParam('limit', $default = 10);
        $limit = (int) $req->getQueryParam('limit');
        $page = (int) $req->getQueryParam('page');

        // setea valores
        $limit = ($limit <= 0) ? 10 : $limit;
        $page = ($page <= 0) ? 1 : $page;

        // calcula la cantidad de registros que debe saltarse para la paginacion
        $offset = ($page - 1) * $limit;

        $data = $this->model->rol->listar($limit, $offset);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener los roles';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Roles obtenidos correctamente';
            $response->data = $data;
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene rol por id
    // ===============================================================
    $this->get('/{id}', function ($req, $res, $args) {
        $response = new Response;
        $data = $this->model->rol->obtener($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener el rol';
            $response->errors = $data;
        } else {
            if (!$data['rol']) {
                $codigo = $response::HTTP_400_BAD_REQUEST;
                $response->success = false;
                $response->message = 'Rol no existe';
            } else {
                $codigo = $response::HTTP_200_OK;
                $response->success = true;
                $response->message = 'Rol obtenido correctamente';
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
