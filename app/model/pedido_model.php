<?php
namespace App\Model;

use App\Lib\Response;

class PedidoModel
{
    private $db;
    private $table = 'pedido';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }
    
    public function listar($l, $p)
    {
        $data = $this->db->from($this->table)
                        ->innerJoin("tabla_de_tablas tt ON tt.relacion = 'pedido_estado' AND tt.id = pedido.Estado_id")
                        ->select('pedido.*, empleado.nombre as Empleado, tt.Valor as Estado')
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
        $row = $this->db->from($this->table, $id)
                        ->innerJoin("tabla_de_tablas tt ON relacion = 'pedido_estado' AND tt.id = pedido.Estado_id")
                        ->select('pedido.*, empleado.Nombre Empleado, tt.Valor Estado')
                        ->fetch();

        $row->{'Detalle'} = $this->db->from('pedido_detalle')
                                     ->select('pedido_detalle.*, producto.Nombre Producto')
                                     ->where('pedido_id', $id)
                                     ->fetchAll();

        return $row;
    }
    
    public function listarPorEmpleado($empleado_id)
    {
        return $this->db->from($this->table)
                        ->where("Empleado_id", $empleado_id)
                        ->innerJoin("tabla_de_tablas tt ON tt.relacion = 'pedido_estado' AND tt.id = pedido.Estado_id")
                        ->select('pedido.*, empleado.nombre as Empleado, tt.Valor as Estado')
                        ->orderBy('id DESC')
                        ->fetchAll();
    }

    public function estados()
    {
        return $this->db->from("tabla_de_tablas")
                        ->where("relacion", "pedido_estado")
                        ->orderBy("orden")
                        ->fetchAll();
    }

    public function actualizaEstados($pedido_id, $estado_id)
    {
        $this->db->update($this->table, [ 'Estado_id' => $estado_id ], $pedido_id)
                ->execute();
        return $this->response->SetResponse(true);
    }

    public function guardar($data)
    {
        //inserta cabecer del pedido
        $pedido_id = $this->db->insertInto($this->table, [
            'Estado_id' => 0,
            'Cliente' => $data['Cliente'],
            'Empleado_id' => $data['Empleado_id'],
            'Total' => $data['Total'],
            'Fecha' => date('y-m-d')
        ])->execute();
        
        //inserta detalle del pedido
        foreach($data['Detalle'] as $d) {
            $this->db->insertInto('pedido_detalle', [
                'Pedido_id' => $pedido_id,
                'Producto_id' => $d['Producto_id'],
                'Cantidad' => $d['Cantidad'],
                'PrecioUnitario' => $d['PrecioUnitario'],
                'Total' => $d['Total']
            ])->execute();
        }
        
        return $this->response->SetResponse(true);
    }
    
}
