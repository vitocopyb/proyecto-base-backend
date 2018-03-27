<?php
namespace App\Model;

use App\Lib\Response,
    App\Lib\Auth;

class AuthModel
{
    private $db;
    private $table = 'empleado';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }
    
    public function autenticar($correo, $password)
    {
        $empleado = $this->db->from($this->table)
                            ->where("correo", $correo)
                            ->where("password", md5($password))
                            ->fetch();
        
        if(is_object($empleado)) {
            $nombre = explode(' ', $empleado->Nombre)[0];
            $token = Auth::SignIn([
                'id' => $empleado->id,
                'EsAdmin' => (bool)$empleado->EsAdmin,
                'Nombre' => $nombre,
                'NombreCompleto' => $empleado->Nombre
                //'Imagen' => $empleado->Imagen
            ]);
            
            $this->response->result = $token;
            return $this->response->SetResponse(true);
        } else {
            return $this->response->SetResponse(false, "Credenciales no válidas");
        }
    }

}
