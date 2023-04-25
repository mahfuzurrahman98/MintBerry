<?php

namespace MintBerry\Core;

trait JSONResponse {
  public static function send($statusCode, $message, $data = null) {
    header('Content-Type: application/json');
    http_response_code($statusCode);

    $response = ['message' => $message];

    if (is_array($data) && empty($data)) {
      $response['data'] = array();
    }

    if ($data != NULL) {
      $response['data'] = $data;
    }

    echo json_encode($response);

    die;
  }
}
