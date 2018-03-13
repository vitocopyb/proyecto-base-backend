<?php
use App\Lib\Auth,
    App\Lib\Response,
    App\Validation\RolValidation,
    App\Middleware\AuthMiddleware;

/*
los metodos se deben implementar de la siguiente manera

GET /roles -> obtiene todos los roles
GET /roles/5 -> obtiene rol del id 5
POST /roles -> crea un nuevo rol
PUT /roles/5 -> actualiza la informacion del rol con id 5
DELETE /roles/5 -> elimina el rol con id 5
*/
	
	
$app->group('/roles', function () {
    /*
    $this->get('listar/{l}/{p}', function ($req, $res, $args) {
		// revisar estos link para obtener los parametros del querystring
		// https://stackoverflow.com/questions/32668186/slim-3-how-to-get-all-get-put-post-variables
		// https://www.codecourse.com/forum/topics/how-to-get-get-put-post-variables-in-slim-3
		
        return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->rol->listar($args['l'], $args['p']))
                );
    });
    */

    // ===============================================================
    // obtiene los roles
    // ===============================================================
    $this->get('', function ($req, $res, $args) {
		// revisar estos link para obtener los parametros del querystring
		//https://stackoverflow.com/questions/32668186/slim-3-how-to-get-all-get-put-post-variables
		//https://www.codecourse.com/forum/topics/how-to-get-get-put-post-variables-in-slim-3
        
        // $limite = (int)$req->getQueryParam('limite', $default = 10);
        $limite = (int)$req->getQueryParam('limite');
        $pagina = (int)$req->getQueryParam('pagina');
        
        // setea valores
        $limite = ($limite <= 0) ? 10 : $limite;
        $pagina = ($pagina <= 0) ? 1 : $pagina;

        // calcula la cantidad de registros que debe saltarse para la paginacion
        $offset = ($pagina - 1) * $limite;

        /*
        TODO *** ACA VOY: crear las mismas respuestas que en el curso Angular Avanzado
        */

        $data = $this->model->rol->listar($limite, $offset);
        $respuesta = [
            'respuesta' => true,
            'mensaje' => 'Petición realizada correctamente',
            'roles' => $data['roles'],
            'total' => $data['total']
        ];

        return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200)
                ->write(json_encode($respuesta));
    });


    // ===============================================================
    // obtiene rol por id
    // ===============================================================
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->rol->obtener($args['id']))
                );
    });

    // ===============================================================
    // graba un nuevo rol
    // ===============================================================
    $this->post('', function ($req, $res, $args) {
        /*
        $r = EmpleadoValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                    ->withStatus(422)
                    ->write(json_encode($r->errors));            
        }
        */

        $data = $this->model->rol->guardar($req->getParsedBody());

        if (isset($data['errors'])) {
            $codigo = 500;
            $respuesta = [
                'respuesta' => false,
                'mensaje' => 'Error al crear rol',
                'errors' => $data
            ];
        } else {
            $codigo = 200;
            $respuesta = [
                'respuesta' => true,
                'mensaje' => 'Rol creado correctamente'
            ];
        }     

        return $res
                ->withHeader('Content-type', 'application/json')
                ->withStatus($codigo)
                ->write(json_encode($respuesta));
    });

    // ===============================================================
    // actualiza un rol
    // ===============================================================
    $this->put('/{id}', function ($req, $res, $args) {
        /*
        $r = EmpleadoValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                    ->withStatus(422)
                    ->write(json_encode($r->errors));            
        }
        */

        return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->rol->actualizar($req->getParsedBody(), $args['id']))
                );
    });

    // ===============================================================
    // elimina un rol
    // ===============================================================
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->rol->eliminar($args['id']))
                );
    });

//})->add( new AuthMiddleware($app) );
});
