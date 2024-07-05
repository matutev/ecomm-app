<?php

namespace App\Libraries;

class JsonHandler
{

    CONST READ_ERROR     = 'No se ha podido leer el archivo JSON';
    CONST INSERT_ERROR   = 'No se ha podido insertar el registro en el archivo JSON';
    CONST UPDATE_ERROR   = 'No se ha podido actualizar el registro en el archivo JSON';
    CONST DELETE_ERROR   = 'No se ha podido eliminar el registro en el archivo JSON';
    CONST READ_SUCCESS   = 'Se ha leido el archivo JSON con exito';
    CONST INSERT_SUCCESS = 'Se ha insertado un registro en el archivo JSON ';
    CONST UPDATE_SUCCESS = 'Se ha actualizado un registro en el archivo JSON';
    CONST DELETE_SUCCESS = 'Se ha eliminado un registro en el archivo JSON';
    CONST EMPTY_FILE     = 'No hay registros';

    /**
     * Lee un archivo json
     * 
     * @param string $file                  la ruta del archivo json
     * 
     * @return array         
     */
    public function read(string $file): array {

        try {
            $data = file_get_contents($file);

            if(!$data){
    
                return $this->getResponse(500, true, [SELF::READ_ERROR]);
            }

            $arrayData = json_decode($data, true);

            $messages  = !count($arrayData) ? [SELF::EMPTY_FILE] : [SELF::READ_SUCCESS];

            return $this->getResponse(200, false, $messages, $arrayData);

        } catch (\Throwable $th) {

            return $this->getResponse(500, true, [SELF::READ_ERROR]);
        }

    }

    /**
     * inserta datos en un archivo json
     * 
     * @param string $file                  la ruta del archivo json
     * @param array $data                   los datos a agregar
     * @param array $row                    el registro nuevo
     * @param string $type                  que tipo de accion se realizara
     * 
     * @return array         
     */
    public function insert(string $file, array $data, array $row, string $type): array {

        switch ($type) {
            case 'insert':
                $messageError   = SELF::INSERT_ERROR;
                $messageSuccess = SELF::INSERT_SUCCESS;
            break;
            case 'update':
                $messageError   = SELF::UPDATE_ERROR;
                $messageSuccess = SELF::UPDATE_SUCCESS;
            break;
            case 'delete':
                $messageError   = SELF::DELETE_ERROR;
                $messageSuccess = SELF::DELETE_SUCCESS;
            break;
        }

        try {
            $dbJson = file_put_contents($file, json_encode($data));

            if(!$dbJson){
    
                return $this->getResponse(500,true,[$messageError]);
            }

            return $this->getResponse(200,false,[$messageSuccess], $row);

        } catch (\Throwable $th) {

            return $this->getResponse(500,true,[$messageError]);
        }

    }

    /**
     * Devuelve la respuesta 
     * 
     * @param int $status               codigo de estado
     * @param bool $error               true si existen errores, false si no los hay
     * @param array $messages           los mensajes que se mostraran
     * @param array $data               datos que se necesitan mostrar
     * 
     * @return array                    ['status'=>int,'error'=>bool,'data'=>array,'messages'=>array]
     */
    public static function getResponse(int $status, bool $error, array $messages = [], array $data = []): array {

        return [
            'status'   => $status,
            'error'    => $error,
            'data'     => $data,
            'messages' => $messages
        ];

    }
}
