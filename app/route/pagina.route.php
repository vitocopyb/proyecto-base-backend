<?php

use App\Lib\Response;
use App\Validation\PaginaValidation;

$app->group('/paginas', function () {

    // ===============================================================
    // crea una nueva página
    // ===============================================================
    $this->post('', function ($req, $res, $args) {
        $response = new Response();
        $errors = PaginaValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al crear una nueva página';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien graba el nuevo registro
        $data = $this->model->pagina->crear($req->getParsedBody());

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al crear una nueva página';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Página creada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // actualiza una página
    // ===============================================================
    $this->put('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $errors = PaginaValidation::validate($req->getParsedBody());

        // si hay errores, termina el proceso
        if (count($errors) > 0) {
            $response->success = false;
            $response->message = 'Error al actualizar una página';
            $response->errors = $errors;

            return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($response::HTTP_400_BAD_REQUEST)
                ->write(json_encode($response->getResponse()));
        }

        // si esta todo bien actualiza el registro
        $data = $this->model->pagina->actualizar($req->getParsedBody(), $args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al actualizar una página';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Página actualizada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // elimina una página
    // ===============================================================
    $this->delete('/{id}', function ($req, $res, $args) {
        $response = new Response();
        $data = $this->model->pagina->eliminar($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al eliminar una página';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Página eliminada correctamente';
        }

        return $res
            ->withHeader('Content-type', 'application/json')
            ->withStatus($codigo)
            ->write(json_encode($response->getResponse()));
    });

    // ===============================================================
    // obtiene las paginas
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

        $data = $this->model->pagina->listar($limit, $offset);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener las páginas';
            $response->errors = $data;
        } else {
            $codigo = $response::HTTP_200_OK;
            $response->success = true;
            $response->message = 'Páginas obtenidas correctamente';
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
        $data = $this->model->pagina->obtener($args['id']);

        if (isset($data['exception'])) {
            $codigo = $response::HTTP_500_INTERNAL_SERVER_ERROR;
            $response->success = false;
            $response->message = 'Error al obtener la página';
            $response->errors = $data;
        } else {
            // si no encontro registros retorna bad request
            if (!$data['pagina']) {
                $codigo = $response::HTTP_400_BAD_REQUEST;
                $response->success = false;
                $response->message = 'Página no existe';
            } else {
                $codigo = $response::HTTP_200_OK;
                $response->success = true;
                $response->message = 'Página obtenida correctamente';
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
