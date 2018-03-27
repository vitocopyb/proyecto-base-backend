<?php
namespace App\Model;

use App\Lib\Response;

class EmpleadoModel
{
    private $db;
    private $table = 'empleado';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }
    
    public function listar($l, $p)
    {
        $data = $this->db->from($this->table)
                         ->limit($l)
                         ->offset($p)
                         ->orderBy('id DESC')
                         ->fetchAll();
        
        $total = $this->db->from($this->table)
                          ->select('COUNT(*) Total')
                          ->fetch()
                          ->Total;
        
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
  
    public function obtener($id)
    {
      return $this->db->from($this->table, $id)
                      //->where("IdEmpleado", $id) // si el identificador no se llama "id", entoces se puede utilizar el where y se quita el $id del update()
                      ->fetch();
    }
    
    public function registrar($data)
    {
        $data['Password'] = md5($data['Password']);
        
        $this->db->insertInto($this->table, $data)
                 ->execute();
        
        return $this->response->SetResponse(true);
    }

    public function actualizar($data, $id)
    {
      if (isset($data['Password'])) {
        //falta validar que si el password viene vacio, entonces debe mantener el password actual
        $data['Password'] = md5($data['Password']);  
      }
        
      $this->db->update($this->table, $data, $id)
               //->where("IdEmpleado", $id) // si el identificador no se llama "id", entoces se puede utilizar el where y se quita el $id del update()
               ->execute();

      return $this->response->SetResponse(true);
    }

    public function eliminar($id)
    {
        $this->db->deleteFrom($this->table, $id)
                 //->where("IdEmpleado", $id) // si el identificador no se llama "id", entoces se puede utilizar el where y se quita el $id del update()
                 ->execute();
        
        return $this->response->SetResponse(true);
    }


}
