<?php

use App\Lib\Response;
use App\Validation\PaginaCategoriaValidation;

$app->group('/pagina-categorias', function () {

    // ===============================================================
    // crea una nueva categoría
    // ===============================================================
    $this->post('', function ($req, $res, $args) {
        $response = new Response();
        $errors = PaginaCategoriaValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al crear una nueva categoría';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien graba el nuevo registro
        $data = $this->model->paginaCategoria->crear($req->getParsedBody());

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al crear una nueva categoría';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Categoría creada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // actualiza una categoría
    // ===============================================================
    $this->put('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $errors = PaginaCategoriaValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al actualizar una categoría';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien actualiza el registro
        $data = $this->model->paginaCategoria->actualizar($req->getParsedBody(), $args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al actualizar una categoría';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Categoría actualizada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // elimina una categoría
    // ===============================================================
    $this->delete('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $data = $this->model->paginaCategoria->eliminar($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al eliminar una categoría';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Categoría eliminada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene las categorias
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

        $data = $this->model->paginaCategoria->listar($limit, $offset);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener las categorías';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Categorías obtenidas correctamente';
            $response->data = $data;
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene categoria por id
    // ===============================================================
    $this->get('/{id}', function ($req, $res, $args) {
        $response = new Response;
        $data = $this->model->paginaCategoria->obtener($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener la categoría';
            $response->errors = $data;
        } else {
            // si no encontro registros retorna bad request
            if (!$data['categoria']) {
                $codigo = $response::HTTP_400_BAD_REQUEST;
                $response->success = false;
                $response->message = 'Categoría no existe';
            } else {
                $codigo = $response::HTTP_200_OK;
                $response->success = true;
                $response->message = 'Categoría obtenida correctamente';
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
