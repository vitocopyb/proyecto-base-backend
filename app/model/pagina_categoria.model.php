<?php

namespace App\Model;

use Exception;

class PaginaCategoriaModel
{
    private $db;
    private $table = 'pagina_categoria';

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ===============================================================
    // crea una nueva categoria
    // ===============================================================
    public function crear($data)
    {
        $respuesta = [];

        try {
            // elimina campo para que lo grabe autoincremental
            unset($data['idPaginaCategoria']);

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
    // actualiza una categoria
    // ===============================================================
    public function actualizar($data, $id)
    {
        $respuesta = [];

        try {
            $this->db
                ->update($this->table, $data)
                ->where("idPaginaCategoria", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // elimina una categoria
    // ===============================================================
    public function eliminar($id)
    {
        $respuesta = [];

        try {
            $this->db
                ->deleteFrom($this->table)
                ->where("idPaginaCategoria", $id)
                ->execute();
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

    // ===============================================================
    // obtiene las categorias
    // ===============================================================
    public function listar($limit, $offset)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->limit($limit)
                ->offset($offset)
                ->orderBy('idPaginaCategoria DESC')
                ->fetchAll();

            $total = $this->db->from($this->table)
                ->select('COUNT(idPaginaCategoria) Total')
                ->fetch()
                ->Total;

            $respuesta = [
                'categorias' => $data,
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
    // obtiene categoria por id
    // ===============================================================
    public function obtener($id)
    {
        $respuesta = [];

        try {
            $data = $this->db->from($this->table)
                ->where("idPaginaCategoria", $id)
                ->fetch();

            $respuesta = [
                'categoria' => $data,
            ];
        } catch (Exception $e) {
            $respuesta = [
                'exception' => [$e->getMessage()],
            ];
        }

        return $respuesta;
    }

}
