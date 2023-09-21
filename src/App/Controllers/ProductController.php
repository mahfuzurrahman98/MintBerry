<?php

namespace MintBerry\App\Controllers;

use MintBerry\Core\Request;
use MintBerry\Core\Session;
use MintBerry\Core\Validator;
use MintBerry\Core\Controller;
use MintBerry\Core\JSONResponse;
use MintBerry\App\Models\Product;

class ProductController extends Controller {
  use JSONResponse;
  protected $model;


  public function __construct() {
    $this->model = new Product();
  }


  public function index() {
    $request = new Request();
    try {
      $this->send(200, 'ProductController@index', $this->model->all());
    } catch (\Exception $e) {
      $this->send(500, $e->getMessage());
    }

    // $products = $this->model->all();

    // $this->render('products.index', [
    //   'products' => $products
    // ]);
  }


  public function show() {
    $request = new Request();

    try {
      if (!$request->hasQueryParam('id')) {
        $this->send(400, 'Missing id query parameter');
      }

      $id = $request->getQueryParam('id');
      $data = $this->model->find($id);

      if (!$data) {
        $this->send(404, 'Product not found');
      }

      $this->send(200, 'Product fetched successfully', $data);
    } catch (\Exception $e) {
      $this->send(500, $e->getMessage());
    }
  }

  public function postRreq() {
    $request = new Request();
    // echo 'csrf: ' . Session::get('csrf_token');
    // dd(Session::all());
    dd($request->getBody());
    // dd($_POST);
  }

  public function store() {
    $request = new Request();

    $rules = [
      'sku' => [
        'type' => ['alpha_num', 'The SKU must be alphanumeric.'],
        'required' => [true, 'SKU is required.'],
        'length_between' => '3,255',
      ],
      'name' => [
        'type' => 'string',
        'unique' => ['products', 'name'],
        'required' => true,
        'length_between' => '3,255',
      ],
      'price' => [
        'type' => 'numeric',
        'required' => false,
        'between' => '14,16'
      ],
      'description' => [
        'required' => false,
        'length_between' => '3,255',
      ],
    ];

    $validator = new Validator($request->getBody(), $rules);
    $validator->run();

    $this->send(200, $validator->getErrors());

    try {
      $product = $this->model->create($request->getBody());
      $this->send(200, 'Product created successfully', $product);
    } catch (\Exception $e) {
      $this->send(500, $e->getTraceAsString());
    }
  }


  public function update() {
    $request = new Request();

    try {
      if (!$request->hasQueryParam('id')) {
        $this->send(400, 'Missing id query parameter');
      }
     
      $id = $request->getQueryParam('id');
      $data = $this->model->find($id);

      if (!$data) {
        $this->send(404, 'Product not found');
      }

      $this->model->update($id, $request->getBody());
      $this->send(200, 'Product updated successfully', $request->getBody());
    } catch (\Exception $e) {
      $this->send(500, $e->getMessage());
    }
  }


  public function delete() {
    $request = new Request();

    try {
      if (!$request->hasQueryParam('id')) {
        $this->send(400, 'Missing id query parameter');
      }

      $id = $request->getQueryParam('id');
      $data = $this->model->find($id);

      if (!$data) {
        $this->send(404, 'Product not found');
      }

      $this->model->delete($id);
      $this->send(200, 'Product deleted successfully');
    } catch (\Exception $e) {
      $this->send(500, $e->getMessage());
    }
  }

  public function test() {
    // abort(500);
    // Session::put('test', 'test');
    // dd(Session::all());

    $this->render('test');
  }
}
