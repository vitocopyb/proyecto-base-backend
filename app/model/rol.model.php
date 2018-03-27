<?php

namespace App\Model;

use Exception;

class RolModel
{
    private $db;
    private $table = 'rol';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ===============================================================
    // crea un nuevo rol
    // ===============================================================
    public function crear($data)
    {
        $respuesta = [];

        try {
            // elimina campo para que lo grabe autoincremental
            unset($data['idRol']);

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
    // actualiza un rol
    // ===============================================================
    public function actualizar($data, $id)
    {
        $respuesta = [];

        try {
            $this->db
                ->update($this->table, $data)
                ->where("idRol", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // elimina un rol
    // ===============================================================
    public function eliminar($id)
    {
        $respuesta = [];

        try {
            $this->db
                ->deleteFrom($this->table)
                ->where("idRol", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // obtiene los roles
    // ===============================================================
    public function listar($limit, $offset)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->limit($limit)
                ->offset($offset)
                ->orderBy('idRol DESC')
                ->fetchAll();

            $total = $this->db->from($this->table)
                ->select('COUNT(idRol) Total')
                ->fetch()
                ->Total;

            $respuesta = [
                'roles' => $data,
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
    // obtiene rol por id
    // ===============================================================
    public function obtener($id)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->where("idRol", $id)
                ->fetch();

            $respuesta = [
                'rol' => $data,
            ];
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

}
