<?php

namespace App\Model;

use App\Lib\Utilities;
use Exception;

class UsuarioModel
{
    private $db;
    private $table = 'usuario';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ===============================================================
    // crea un nuevo usuario
    // ===============================================================
    public function crear($data)
    {
        $respuesta = [];

        try {
            // elimina campo para que lo grabe autoincremental
            unset($data['idUsuario']);

            // encripta la clave
            $data['password'] = Utilities::encriptarPassword($data['password']);
            $data['fechaCreacion'] = Utilities::obtenerFechaHoraActual();
            $data['fechaModificacion'] = Utilities::obtenerFechaHoraActual();

            $this->db
                ->insertInto($this->table, $data)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // actualiza un usuario
    // ===============================================================
    public function actualizar($data, $id)
    {
        $respuesta = [];

        try {
            // si no viene vacio entonces encripta la nueva clave
            if (!empty($data['password'])) {
                $data['password'] = Utilities::encriptarPassword($data['password']);
            } else {
                // lo quita del objeto para no actualizarlo
                unset($data['password']);
            }
            
            $data['fechaModificacion'] = Utilities::obtenerFechaHoraActual();

            $this->db
                ->update($this->table, $data)
                ->where("idUsuario", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // elimina un usuario
    // ===============================================================
    public function eliminar($id)
    {
        $respuesta = [];

        try {
            $this->db
                ->deleteFrom($this->table)
                ->where("idUsuario", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // obtiene los usuarios
    // ===============================================================
    public function listar($limit, $offset)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->select(NULL) // setea select para que no obtenga tabla.*
                ->select('idUsuario, idRol, nombre, paterno, materno, username, tipoIdentificacion, identificacion, telefono, email, activo')
                ->limit($limit)
                ->offset($offset)
                ->orderBy('idUsuario DESC')
                ->fetchAll();

            $total = $this->db->from($this->table)
                ->select('COUNT(idUsuario) Total')
                ->fetch()
                ->Total;

            $respuesta = [
                'usuarios' => $data,
                'total' => $total,
            ];
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // obtiene usuario por id
    // ===============================================================
    public function obtener($id)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->select(NULL) // setea select para que no obtenga tabla.*
                ->select('idUsuario, idRol, nombre, paterno, materno, username, tipoIdentificacion, identificacion, telefono, email, activo')
                ->where("idUsuario", $id)
                ->fetch();

            $respuesta = [
                'usuario' => $data,
            ];
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

}
