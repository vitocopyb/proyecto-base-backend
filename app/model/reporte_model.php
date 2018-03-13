<?php
namespace App\Model;

use App\Lib\Response;

class ReporteModel
{
    private $db;
    private $response;
    
    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }
    
    public function topEmpleado()
    {
        /*
        SELECT
            IFNULL(e.Imagen,'') Imagen,
            e.Nombre,
            COUNT(p.id) AS Pedidos
        FROM
            empleado AS e
            LEFT JOIN pedido AS p on (e.id = p.Empleado_id and p.Estado_id = 1 )
        GROUP BY
            e.id
        ORDER BY
            Pedidos DESC
        */
        
        return $this->db->from("empleado e")
                        ->select(NULL) // setea select para que no obtenga tabla.*
                        ->select("IFNULL(e.Imagen,'') AS Imagen, e.Nombre, COUNT(p.id) Pedidos")
                        ->leftJoin("pedido AS p on (e.id = p.Empleado_id and p.Estado_id = 1 )")
                        ->groupBy('e.id')
                        ->orderBy('Pedidos DESC')
                        ->fetchAll();
    }
    
    public function topProducto()
    {
        /*
        SELECT
            pr.Imagen,
            pr.Nombre,
            IFNULL(SUM(pd.Cantidad),0) AS Cantidad,
            IFNULL(SUM(pd.Total),0) AS Total
        FROM
            producto AS pr
            LEFT JOIN pedido_detalle AS pd ON (pr.id = pd.Producto_id)
            LEFT JOIN pedido AS p ON (p.id = pd.Pedido_id and p.Estado_id = 1)
        GROUP BY
            pr.Id
        ORDER BY
            Cantidad DESC, Total DESC
        */
        
        return $this->db->from("producto pr")
                        ->select(NULL) // setea select para que no obtenga tabla.*
                        ->select("IFNULL(pr.Imagen,'') Imagen, pr.Nombre, IFNULL(SUM(pd.Cantidad),0) AS Cantidad, IFNULL(SUM(pd.Total),0) AS Total")
                        ->leftJoin("pedido_detalle pd ON (pr.id = pd.Producto_id)")
                        ->leftJoin("pedido p ON p.id = pd.Pedido_id and p.Estado_id = 1")
                        ->groupBy('pr.Id')
                        ->orderBy('Cantidad DESC, Total DESC')
                        ->fetchAll();
    }

    public function ventaMensual()
    {
        /*
        SELECT
            CONCAT(YEAR(p.Fecha), ', ', MONTH(p.Fecha)) Periodo,
            SUM(p.Total) Total
        FROM
            pedido AS p
        WHERE
            p.Estado_id = 1            
        GROUP BY
            YEAR(p.Fecha), MONTH(p.Fecha)
        ORDER BY
            Periodo DESC	
        */
        
        return $this->db->from("pedido p")
                        ->select(NULL) // setea select para que no obtenga tabla.*
                        ->select("CONCAT(YEAR(p.Fecha), ', ', MONTH(p.Fecha)) Periodo, SUM(p.Total) Total")
                        ->where("p.Estado_id = 1")
                        ->groupBy('YEAR(p.Fecha), MONTH(p.Fecha)')
                        ->orderBy('Periodo DESC')
                        ->fetchAll();
    }

}