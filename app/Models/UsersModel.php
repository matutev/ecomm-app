<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\JsonHandler;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user','pass','role'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [
        401 => 'No se ha encontrado el usuario',
        402 => 'La contraseña es invalida'
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // URL DB
    CONST URL_DB_USERS              = 'app/Database/Users/users.json';
    // Roles
    CONST ROLE_ADMIN                = 'admin';
    CONST ROLE_USER                 = 'user';

    /**
     * chequea si el usuario y la contraseña son validas
     * 
     * @param array $params               el user y el pass  
     * 
     * @return boolean
     */
    public function login(array $params){

        //busca la data del usuario
        $response = $this->findByUser($params['user']);

        if($response['error']){
            return $response;
        }

        //valida la contraseña
        $respValidationPass = $this->validatePass($response['data']['pass'], $params['pass']);
        
        if($respValidationPass['error']){
            return $respValidationPass;
        }

        //guarda datos en session
        session()->set([
            'id_user'   => $response['data']['id'],
            'user'      => $response['data']['user'],
            'role'      => $response['data']['role'],
            'logged'    => true
        ]);
       
        return JsonHandler::getResponse(200, false, ['Bienvenido '.$params['user']], $response['data']);
    }

    /**
     * Devuelve todos los registros
     * 
     * @return array         
     */
    public function fetchAll(): array
    {   

        $jsonHandler = new JsonHandler();
        $response    = $jsonHandler->read(SELF::URL_DB_USERS);

        return $response;
    }

    /**
     * Destruye la sesion
     */
    public function logOut(){
        session()->destroy();     
    }

    /**
     * Devuelve todos los roles
     * 
     * @return array
     */
    public static function getRoles(){
        return [
            SELF::ROLE_ADMIN,
            SELF::ROLE_USER
        ];   
    }

    /**
     * Devuelve el registro por user
     * 
     * @param string $userName                 el nombre del usuario ingresado en el login
     * 
     * @return array         
     */
    private function findByUser(string $userName): array
    {   
        $response    = $this->fetchAll();

        if($response['error']){
            return $response;
        }

        //filtra los registros por el user
        $found = array_filter($response['data'],function($v,$k) use ($userName){
            return $v['user'] == $userName;
          },ARRAY_FILTER_USE_BOTH);

        $rows  =  array_values($found);
        $data  =  end($rows);

        if(!count($rows)){
            return JsonHandler::getResponse(400, true, [$this->validationMessages[401]]);
        }
 
        return JsonHandler::getResponse(200, false, [], $data);
    }


    /**
     * Validar contraseña
     * 
     * @param string $userPass                 la contraseña de la db
     * @param string $currentPass              la contraseña ingresada en el login
     * 
     * @return array         
     */
    private function validatePass(string $userPass, string $currentPass): array
    {   
        if(!password_verify($currentPass, $userPass)){
            return JsonHandler::getResponse(400, true, [$this->validationMessages[402]]);
        }

       return JsonHandler::getResponse(200, false);
    }

    

}
