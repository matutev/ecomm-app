<?php

namespace App\Controllers;

use App\Models\ProductosModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;


class ProductosModelTest extends CIUnitTestCase
{

    use FeatureTestTrait;

    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new ProductosModel();
    }

    public function testInsertRow(){

        $params = [
            'title' => 'auriculares',
            'price' => '2'
        ];

        $response = $this->model->insertRow($params);

        var_dump($response);

        $this->assertArrayHasKey('status',$response);
        $this->assertArrayHasKey('error',$response);
        $this->assertArrayHasKey('data',$response);
        $this->assertArrayHasKey('messages',$response);

        $this->assertEquals(200,$response['status']);
        
    }

    public function testUpdateRow(){

        $id = 40;
        
        $params = [
            'title' => 'auriculares',
            'price' => '2'
        ];

        $response = $this->model->updateRow($id, $params);

        var_dump($response);

        $this->assertArrayHasKey('status',$response);
        $this->assertArrayHasKey('error',$response);
        $this->assertArrayHasKey('data',$response);
        $this->assertArrayHasKey('messages',$response);

        $this->assertEquals(200,$response['status']);
        
    }

    public function testDeleteRow(){

        $id = 40;

        $response = $this->model->deleteRow($id);

        var_dump($response);

        $this->assertArrayHasKey('status',$response);
        $this->assertArrayHasKey('error',$response);
        $this->assertArrayHasKey('data',$response);
        $this->assertArrayHasKey('messages',$response);

        $this->assertEquals(200,$response['status']);
        
    }

    public function testFindByField(){

        $value = '';

        $response = $this->model->findByField($value);

        var_dump($response);

        $this->assertArrayHasKey('status',$response);
        $this->assertArrayHasKey('error',$response);
        $this->assertArrayHasKey('data',$response);
        $this->assertArrayHasKey('messages',$response);

        $this->assertEquals(200,$response['status']);
        
    }


}