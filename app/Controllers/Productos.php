<?php

namespace App\Controllers;

use App\Libraries\Utility;
use App\Libraries\JsonHandler;
use App\Libraries\LogsHandler;
use App\Models\ProductosModel;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Libraries\PaginatorHandler;

class Productos extends BaseController
{

    use ResponseTrait;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $productosModel   = new ProductosModel();

        $response         = $productosModel->fetchAll();
        $paginatorHandler = new PaginatorHandler(1, $response['data']);

        //si devolvio error al leer los registros
        if($response['error']){
            return view('productos/index', array_merge($response, ['pagination' => $paginatorHandler->getPropertiesToArray()]));
        }
        //paginacion
        $response         = $productosModel->rowsByPage($paginatorHandler, $response['data']);

        return view('productos/index', array_merge($response, ['pagination' => $paginatorHandler->getPropertiesToArray()]));
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        return view('productos/new');
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
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

        $post = $this->request->getPost(['title','price']);

        //validacion caracteres especiales
        if(Utility::validateSpecialChars($post)){
            $response = JsonHandler::getResponse(400,true,['No se permiten caracteres especiales']);

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $productosModel = new ProductosModel();
        $response       = $productosModel->insertRow([
            'title' => trim($post['title']),
            'price' => trim($post['price'])
        ]);

        //si devolvio error al insertar producto
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }
        //registro de operacion
        LogsHandler::setLogAction(__FUNCTION__,$response['data']);
  
        return $this->respondCreated(array_merge(
            $response, 
            ['csrf_token' => csrf_hash()]));
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        $productosModel = new ProductosModel();

        if(is_null($id) || Utility::validateSpecialChars([$id])){
            return redirect()->to('productos');
        }

        $productosModel = new ProductosModel();
        $response       = $productosModel->findById($id);

        if($response['error']){
            return redirect()->to('productos');
        }
        
        return view('productos/edit',$response);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {   
        if(!$this->request->is('put') || is_null($id) || Utility::validateSpecialChars([$id])){
            $response = JsonHandler::getResponse(405,true,['No es una petision valida']);

            return $this->respond($response);
        }

        if(!$this->validate($this->getValidationRules())){
            $response = JsonHandler::getResponse(400,true,$this->validator->getErrors());

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $data = $this->request->getRawInput();

        //validacion caracteres especiales
        if(Utility::validateSpecialChars($data)){
            $response = JsonHandler::getResponse(400,true,['No se permiten caracteres especiales']);
            
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $productosModel = new ProductosModel();
        $response       = $productosModel->updateRow($id, [
            'title' => trim($data['title']),
            'price' => trim($data['price'])
        ]);

        //si devolvio error al actualizar el producto
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }
        //registro de operacion
        LogsHandler::setLogAction(__FUNCTION__,$response['data']);

        return $this->respondUpdated(array_merge(
            $response, 
            ['csrf_token' => csrf_hash()]));

    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if(!$this->request->is('delete') || is_null($id) || Utility::validateSpecialChars([$id])){
            $response = JsonHandler::getResponse(405,true,['No es una petision valida']);

            return $this->respond($response);
        }

        $productosModel = new ProductosModel();
        $response       = $productosModel->deleteRow($id);

        //si devolvio error al eliminar el producto
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }
        //registro de operacion
        LogsHandler::setLogAction(__FUNCTION__,$response['data']);

        return $this->respondDeleted(array_merge(
            $response, 
            ['csrf_token' => csrf_hash()]));
    }

    /**
     * Go to find results by search field to the model
     *
     *
     * @return ResponseInterface
     */
    public function search()
    {    
        if(!$this->request->is('post')){
            $response = JsonHandler::getResponse(405,true,['No es una petision valida']);

            return $this->respond($response);
        }

        if(!$this->validate(['search' => 'required'])){
            $response = JsonHandler::getResponse(400,true,$this->validator->getErrors());

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $post = $this->request->getPost(['search']);

        //validacion caracteres especiales
        if(Utility::validateSpecialChars($post)){
            $response = JsonHandler::getResponse(400,true,['No se permiten caracteres especiales']);

            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }

        $productosModel     = new ProductosModel();
        $response           = $productosModel->findByField($post['search']);
        $paginatorHandler   = new PaginatorHandler(1, $response['data']);

        //si devolvio error al buscar un producto
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }
        //paginacion
        $response           = $productosModel->rowsByPage($paginatorHandler, $response['data'], $response['messages']);

        //registro de operacion
        LogsHandler::setLogAction(__FUNCTION__,$response['data']);

        return $this->respond(array_merge(
            $response, 
            ['pagination' => $paginatorHandler->getPropertiesToArray(),
            'csrf_token' => csrf_hash()]));

    }

    /**
     * Go to find results by page to the model
     *
     *
     * @return ResponseInterface
     */
    public function searchByPage()
    {            
        $post = $this->request->getPost(['page']);

        if(!$this->request->is('post') || is_null($post['page']) || Utility::validateSpecialChars([$post['page']])){
            $response = JsonHandler::getResponse(405,true,['No es una petision valida']);

            return $this->respond($response);
        }

        $productosModel     = new ProductosModel();
        $response           = $productosModel->fetchAll();
        $paginatorHandler   = new PaginatorHandler($post['page'], $response['data']);

        //si devolvio error al buscar los producto
        if($response['error']){
            return $this->respond(array_merge(
                $response, 
                ['csrf_token' => csrf_hash()]));
        }
        //paginacion
        $response = $productosModel->rowsByPage($paginatorHandler, $response['data'], $response['messages']);

        return $this->respond(array_merge(
            $response, 
            ['pagination' => $paginatorHandler->getPropertiesToArray(), 
            'csrf_token' => csrf_hash()]));

    }

    /**
     * return the validation rules of fields
     * 
     * @return array
     */
    private function getValidationRules():array {

        return [
            'title' => 'required|max_length[30]',
            'price' => 'required|numeric|max_length[15]'
        ];

    }

}
