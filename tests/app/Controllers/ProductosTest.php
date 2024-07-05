<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;

class ProductosTest extends CIUnitTestCase
{

    use ControllerTestTrait;

    public function testIndex(){

        $result = $this->controller(Productos::class)->execute('index');

        $this->assertTrue($result->isOK());
    }

    public function testCreate(){

        $request = new \CodeIgniter\HTTP\IncomingRequest(
            new \Config\App(),
            new \CodeIgniter\HTTP\URI('http://localhost'),
            null,
            new \CodeIgniter\HTTP\UserAgent()
        );

        $result = $this->withRequest($request)->controller(Productos::class)->execute('create');

        $response = $result->response();

        var_dump($response->getBody());

        $this->assertTrue($result->isOK());
        
    }

    public function testUpdate(){

        $request = new \CodeIgniter\HTTP\IncomingRequest(
            new \Config\App(),
            new \CodeIgniter\HTTP\URI('http://localhost'),
            null,
            new \CodeIgniter\HTTP\UserAgent()
        );

        $result = $this->withRequest($request)->controller(Productos::class)->execute('update');

        $response = $result->response();

        var_dump($response->getBody());

        $this->assertTrue($result->isOK());
        
    }

    public function testDelete(){

        $request = new \CodeIgniter\HTTP\IncomingRequest(
            new \Config\App(),
            new \CodeIgniter\HTTP\URI('http://localhost'),
            null,
            new \CodeIgniter\HTTP\UserAgent()
        );

        $result = $this->withRequest($request)->controller(Productos::class)->execute('delete');

        $response = $result->response();

        var_dump($response->getBody());

        $this->assertTrue($result->isOK());
        
    }
}