<?php

namespace App\Controllers;

use App\Libraries\Utility;
use App\Libraries\JsonHandler;
use App\Libraries\LogsHandler;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        return view('users/login');
    }

    /**
     * Check if is valid user and pass in user model
     *
     *
     * @return ResponseInterface
     */
    public function login()
    {    
        if(!$this->request->is('post')){
            $response = JsonHandler::getResponse(405,true,['No es una petision valida']);

            return $this->respond($response);
        }

        if(!$this->validate($this->getValidationRules())){
            $response = JsonHandler::getResponse(400,true,$this->validator->getErrors());

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $post = $this->request->getPost(['user','pass']);

        //validacion caracteres especiales
        if(Utility::validateSpecialChars($post)){
            $response = JsonHandler::getResponse(400,true,['No se permiten caracteres especiales']);

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $userModel          = new UsersModel();
        $response           = $userModel->login([            
            'user' => trim($post['user']),
            'pass' => trim($post['pass'])]);

        //si devolvio error al validar login
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        //registro de operacion
        LogsHandler::setLogAction(__FUNCTION__,$response['data']);

        return $this->respond(array_merge(
            $response, 
            ['csrf_token' => csrf_hash()]));
    }

    /**
     * Destroy session in user model
     *
     *
     * @return ResponseInterface
     */
    public function logOut()
    {    
        $userModel = new UsersModel();
        $userModel->logOut();

        return redirect()->route('/');
    }

    /**
     * return the validation rules of fields
     * 
     * @return array
     */
    private function getValidationRules():array {

        return [
            'user' => 'required',
            'pass' => 'required'
        ];

    }

}
