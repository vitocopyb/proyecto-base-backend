<?php
namespace App\Model;

use App\Lib\Response, Exception;

class RolModel
{
    private $db;
    private $table = 'rol';
    private $response;
    
    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }
    
    public function listar($l, $p)
    {

        try {
            // throw new Exception('Division por cero.');

            $data = $this->db->from($this->table)
                            ->limit($l)
                            ->offset($p)
                            ->orderBy('IdRol DESC')
                            ->fetchAll();

            $total = $this->db->from($this->table)
                            ->select('COUNT(IdRol) Total')
                            ->fetch()
                            ->Total;
            return [
                'roles' => $data,
                'total' => $total
            ];

        } catch (Exception $e) {
            // echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            return [
                'errors' => $e->getMessage()
            ];
        }

    }
  
    public function obtener($id)
    {
      return $this->db->from($this->table)
                    ->where("IdRol", $id)
                    ->fetch();
    }
    
    public function guardar($data) {
        try {
            // elimina campo para que lo grabe autoincremental
            unset($data['idRol']);

            $this->db->insertInto($this->table, $data)
                    ->execute();
    
            // return $this->response->SetResponse(true);
            return [
                'respuesta' => true
            ];
        } catch (Exception $e) {
            // echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            return [
                'errors' => $e->getMessage()
            ];
        }
    }

    public function actualizar($data, $id)
    {
        $this->db->update($this->table, $data)
                ->where("IdRol", $id)
                ->execute();

      return $this->response->SetResponse(true);
    }

    public function eliminar($id)
    {
        $this->db->deleteFrom($this->table)
                ->where("IdRol", $id)
                ->execute();
        
        return $this->response->SetResponse(true);
    }

}
