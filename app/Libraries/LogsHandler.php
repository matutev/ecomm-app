<?php

namespace App\Libraries;

class LogsHandler
{
    CONST CREATE_ACTION  = 'Creacion de un producto';
    CONST UPDATE_ACTION  = 'Actualizacion de los campos title y price de producto';
    CONST DELETE_ACTION  = 'Eliminacion de un producto';
    CONST SEARCH_ACTION  = 'Busqueda de un producto por title | price | created_at';
    CONST LOGIN_ACTION   = 'Se logea exitosamente un usuario';

    /**
     * setea en un archivo txt un registro de log para diferentes acciones
     * 
     * @param string $action          la accion que ejecuto el usuario
     * @param array  $data            datos que se desean registrar en el log
     */
    public static function setLogAction(string $action, array $data){

        switch ($action) {
            case 'create':
                $description   = SELF::CREATE_ACTION;
            break;
            case 'update':
                $description   = SELF::UPDATE_ACTION;
            break;
            case 'delete':
                $description   = SELF::DELETE_ACTION;
            break;
            case 'search':
                $description   = SELF::SEARCH_ACTION;
            break;
            case 'login':
                $description   = SELF::LOGIN_ACTION;
            break;
        }

        $date       = date("d/m/y G:i:s");
        $log        = [$date, 'method: '.$action, $description ,json_encode($data)];

        $file = fopen("log.txt", "a+");

        fwrite($file, implode(' - ',$log).PHP_EOL);
        fclose($file);
    }

}
