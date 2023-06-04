<?php

namespace MintBerry\App\Controllers;

use MintBerry\Core\Request;
use MintBerry\Core\JSONResponse;
use MintBerry\App\Models\Product;
use MintBerry\Core\Controller;
use MintBerry\Core\Session;

class ProductController extends Controller {
  use JSONResponse;
  protected $model;


  public function __construct() {
    $this->model = new Product();
  }


  public function index() {
    // $request = new Request();
    // try {
    //   $this->send(200, 'ProductController@index', $this->model->all());
    // } catch (\Exception $e) {
    //   $this->send(500, $e->getMessage());
    // }

    $products = $this->model->all();

    $this->render('products.index', [
      'products' => $products
    ]);
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
    dd(Session::all());
  }

  public function store() {
    $request = new Request();

    $rules = [
      'sku' => 'required|min:3|max:255|unique:products',
      'name' => 'required|min:3|max:255',
      'price' => 'required|numeric',
      'description' => 'required|min:3|max:255'
    ];

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
    dd(Session::all());
  }
}
