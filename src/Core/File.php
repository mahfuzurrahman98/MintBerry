<?php

namespace MintBerry\Core;

use Exception;

class File {
  protected $file;

  public function __construct($file) {
    $this->file = $file;
  }

  public function validate($allowedExtensions, $maxSize) {
    $file = $this->file;
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileSize = $file['size'];

    if (!in_array($fileExtension, $allowedExtensions)) {
      throw new Exception('File extension not allowed');
    }

    if ($fileSize > $maxSize) {
      throw new Exception('File size exceeded');
    }

    return true;
  }

  public function getSize() {
    return $this->file['size'];
  }

  public function getExtension() {
    return pathinfo($this->file['name'], PATHINFO_EXTENSION);
  }

  public function getName() {
    return pathinfo($this->file['name'], PATHINFO_FILENAME);
  }

  public function upload($destination, $prefix = null) {
    $file = $this->file;
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . '.' . $fileExtension;
    $destination = $destination . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
      throw new Exception('File upload failed');
    }

    return $fileName;
  }
}
