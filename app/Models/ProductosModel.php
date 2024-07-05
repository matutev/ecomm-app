<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\JsonHandler;
use App\Libraries\PaginatorHandler;
use DateTime;

class ProductosModel extends Model
{
    protected $table            = 'productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title','price'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    CONST URL_DB             = 'app/Database/Productos/productos.json';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [
        401 => 'No se ha encontrado el registro',
        402 => 'No se ha encontrado ninguna coincidencia',
        403 => 'Ya se ha cargado este registro'
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Inserta un nuevo registro en el archivo json
     * 
     * @param array $params            los parametros del post
     * @return array          
     */
    public function insertRow(array $params): array
    {   
        $jsonHandler = new JsonHandler();
        $response    = $jsonHandler->read(SELF::URL_DB);

        if($response['error']){
            return $response;
        }

        //filtra los registros buscando por title y price
        $found = array_filter($response['data'],function($v,$k) use ($params){
            return $v['title'] == $params['title'] && $v['price'] == $params['price'];
            },ARRAY_FILTER_USE_BOTH);
        
        if(count(array_values($found))){
            return JsonHandler::getResponse(400,true,[$this->validationMessages[403]]);
        }

        $dbJson     = $response['data'];
        $getLastKey = array_key_last($dbJson);
        $lastId     = $getLastKey ? $dbJson[$getLastKey]['id'] : 0;

        $dataTime = new DateTime();

        $product = [
            'id'            => $lastId+1,
            'title'         => $params['title'],
            'price'         => (float)$params['price'],
            'created_at'    => $dataTime->format('Y-m-d H:i')
        ];

        array_push($dbJson,$product);

        return $jsonHandler->insert(SELF::URL_DB, $dbJson, $product, 'insert');
    }

    /**
     * Actualizar un registro en el archivo json
     * 
     * @param int $id                  el id del registro a actualizar
     * @param array $params            los parametros del post
     * 
     * @return array          
     */
    public function updateRow(int $id, array $params): array
    { 
        $jsonHandler = new JsonHandler();
        $response    = $jsonHandler->read(SELF::URL_DB);

        if($response['error']){
            return $response;
        }

        $dbJson  = $response['data'];
        $existId = false;

        //recorre los registros, busca por id y actualiza los datos
        foreach ($dbJson as $key => $product) {
            if ($product['id'] == $id) {

                $existId               = true;
                $dbJson[$key]['title'] = $params['title'];
                $dbJson[$key]['price'] = (float)$params['price'];

                break;
            }
        }

        if(!$existId){
            return JsonHandler::getResponse(400,true,[$this->validationMessages[401]]);
        }

        return $jsonHandler->insert(SELF::URL_DB, $dbJson, $product, 'update');
    }

    /**
     * Elimina un registro en el archivo json
     * 
     * @param int $id                  el id del registro a eliminar
     * 
     * @return array          
     */
    public function deleteRow(int $id): array
    {   
        $jsonHandler = new JsonHandler();
        $response    = $jsonHandler->read(SELF::URL_DB);

        if($response['error']){
            return $response;
        }

        $dbJson     = $response['data'];
        $existId    = false;
        $row        = [];

        //recorre los registros, busca por id y lo remueve
        foreach ($dbJson as $key => $product) {
            if ($product['id'] == $id) {

                $existId = true;
                $row = $product;
                unset($dbJson[$key]);

                break;
            }
        }

        if(!$existId){
            return JsonHandler::getResponse(400,true,[$this->validationMessages[401]]);
        }

        return $jsonHandler->insert(SELF::URL_DB, $dbJson, $row, 'delete');
    }

    /**
     * Devuelve todos los registros
     * 
     * @return array         
     */
    public function fetchAll(): array
    {   

        $jsonHandler = new JsonHandler();
        $response    = $jsonHandler->read(SELF::URL_DB); 

        return $response;
    }

    /**
     * Devuelve el registro buscado por id
     * 
     * @param int $id            el id del registro buscado
     * 
     * @return array         
     */
    public function findById(int $id): array
    {          
        $response    = $this->fetchAll();

        if($response['error']){
            return $response;
        }
        //filtra los registros buscando por id
        $found = array_filter($response['data'],function($v,$k) use ($id){
            return $v['id'] == $id;
          },ARRAY_FILTER_USE_BOTH);

        $row =  array_values($found);
        
        if(!count($row)){
            return JsonHandler::getResponse(400,true,[$this->validationMessages[401]]);
        }

        return JsonHandler::getResponse(200,false,[],end($row));
    }

    /**
     * Devuelve el/los registro/s buscado/s por title | price | created_at
     * 
     * @param string $value                        el valor que se ingreso en el campo de busqueda
     * 
     * @return array         
     */
    public function findByField(string $value): array
    {   
        $response    = $this->fetchAll();

        if($response['error']){
            return $response;
        }
        //si es una fecha, formatear a YYYY-MM-DD
        $regexDate = '/^([0-2][0-9]|3[0-1])(\/)(0[1-9]|1[0-2])\2(\d{4})$/';
        $date      = false;
        if(preg_match($regexDate,$value)){
            $date = DateTime::createFromFormat('d/m/Y', $value);
            $value = $date->format('Y-m-d');
        }
        //filtra los registros por el campo buscado
        $found = array_filter($response['data'],function($v,$k) use ($value,$date){

            return  strpos($v['title'], $value)  !== false || 
                    strpos($v['price'], $value)  !== false || 
                    ($date && strpos($v['created_at'], $value)  !== false);
        
          },ARRAY_FILTER_USE_BOTH);

        $rows     =  array_values($found);
        $messages =  !count($rows) ? [$this->validationMessages[402]] : [];

        return JsonHandler::getResponse(200,false,$messages,$rows);
    }

    /**
     * Devuelve los registros por pagina
     * 
     * @param PaginatorHandler $paginatorHandler  
     * @param array $data                               los registros encontrados
     * @param array $messages                           mensajes a mostrar          
     * 
     * @return array         
     */
    public function rowsByPage(PaginatorHandler $paginatorHandler, array $data, array $messages = []): array
    {   
        $rows  =  array_splice($data, $paginatorHandler->getOffset(), $paginatorHandler::ROWS_PER_PAGE);

        return JsonHandler::getResponse(200,false,$messages,$rows);
    }

}
