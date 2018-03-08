<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Usuarios extends REST_Controller
{
    public function __contruct()
    {
        parent::__contruct();

        $this->load->database();
        $this->load->helper('url');
    }
    public function obtener_get( $id = 0 )
    {
        
        $usuarios = [];

        if($id > 0){

            $this->db->where('id', $id);
            
        }
        
        $usuarios = $this->db->get('usuarios')->result_array();

        if(!empty($usuarios)){
            
            $this->set_response([
                'status' => TRUE,
                'message' => '',
                'result' => $usuarios
                ],
                REST_Controller::HTTP_OK // Respuesta 200 OK
            );
        } else {
            
            $this->set_response([
                'status' => FALSE,
                'message' => 'Usuarios no encontrados',
                'result' => [],
                REST_Controller::HTTP_NOT_FOUND // Respuesta 404
                ]
            );
        }
    }

    public function eliminar_delete($id)
    {
        $this->db->where('id', $id);

        $this->db->delete('usuarios');

        $this->set_response([
            'id' => $id,
            'message' => 'Registro eliminado',
            'result' => []
        ], REST_Controller::HTTP_OK);
    }

    public function insertar_post(){

        // Obtener datos del cuerpo(body)
        $datos = file_get_contents('php://input');

        // Decodificar json
        $usuario = json_decode($datos);

        // insertar registro
        $this->db->insert('usuarios', $usuario);

        // Obtener último registro insertado
        $usuario->id = $this->db->insert_id();

        // Retornar el registro insertado junto con el id
        $this->set_response($usuario, REST_Controller::HTTP_CREATED);
    }

    public function actualizar_put()
    {
        // Obtener datos del cuerpo(body)
        $datos = file_get_contents('php://input');

        // Decodificar json
        $usuario = json_decode($datos);

        // Especificar el id del registro
        $this->db->where('id', $usuario->id);

        // Actualizar la información
        $this->db->update('usuarios', $usuario);

        // Retornar el registro con el registro actualizado
        $this->set_response([
            'id' => $usuario->id,
            'message' => 'Registro actualizado',
            'result'  => []
        ], REST_Controller::HTTP_OK);

    }
}