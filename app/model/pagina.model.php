<?php

namespace App\Model;

use Exception;

class PaginaModel
{
    private $db;
    private $table = 'pagina';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ===============================================================
    // crea una nueva pagina
    // ===============================================================
    public function crear($data)
    {
        $respuesta = [];

        try {
            // elimina campo para que lo grabe autoincremental
            unset($data['idPagina']);

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
    // actualiza una pagina
    // ===============================================================
    public function actualizar($data, $id)
    {
        $respuesta = [];

        try {
            if (!isset($data['idPaginaPadre'])) {
                $data['idPaginaPadre'] = null;
            } else {
                if ($data['idPaginaPadre'] == '-1') {
                    $data['idPaginaPadre'] = null;
                }
            }

            $this->db
                ->update($this->table, $data)
                ->where("idPagina", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // elimina una pagina
    // ===============================================================
    public function eliminar($id)
    {
        $respuesta = [];

        try {
            $this->db
                ->deleteFrom($this->table)
                ->where("idPagina", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // obtiene las paginas
    // ===============================================================
    public function listar($limit, $offset)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->limit($limit)
                ->offset($offset)
                ->orderBy('idPagina DESC')
                ->fetchAll();

            $total = $this->db->from($this->table)
                ->select('COUNT(idPagina) Total')
                ->fetch()
                ->Total;

            $respuesta = [
                'paginas' => $data,
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
    // obtiene pagina por id
    // ===============================================================
    public function obtener($id)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->where("idPagina", $id)
                ->fetch();

            $respuesta = [
                'pagina' => $data,
            ];
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

}
