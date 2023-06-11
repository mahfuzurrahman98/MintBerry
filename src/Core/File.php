<?php

namespace MintBerry\Core;

use Exception;

class File {
  protected $file;

  public function __construct($file) {
    $this->file = $file;
  }

  public function upload($destination, $prefix = null) {
    if ($this->file['error'] === UPLOAD_ERR_OK) {
      $filename = $this->file['name'];
      $tempPath = $this->file['tmp_name'];
      $destinationPath = $destination . '/' . $filename;

      if (move_uploaded_file($tempPath, $destinationPath)) {
        return $filename;
      } else {
        throw new Exception('Failed to upload the file.');
      }
    } else {
      throw new Exception('Error: ' . $this->file['error']);
    }
  }
}
