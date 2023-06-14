<?php

namespace MintBerry\Core;

class Validator {
  private $errors = [];
  private $rules = [];
  private $data = [];

  public function __construct($data, $rules) {
    $this->data = $data;
    $this->rules = $rules;
  }

  /* This is the format how we set a    daata to validate
  $rules = [
      'sku' => [
        'required' => true,
        'between' => [3, 255],
      ],
      'name' => [
        'required' => true,
        'between' => [3, 255],
      ],
      'price' => [
        'required' => true,
        'numeric' => true,
      ],
      'description' => [
        'required' => true,
        'between' => [3, 255],
      ],
    ];
    */

  public function required($field) {
    if (empty($this->data[$field])) {
      $this->errors[$field][] = 'The ' . $field . ' field is required';
    }
  }

  public function between($field, $range) {
    $min = $range[0];
    $max = $range[1];
    $value = $this->data[$field];

    if (strlen($value) < $min || strlen($value) > $max) {
      $this->errors[$field][] = 'The ' . $field . ' field must be between ' . $min . ' and ' . $max . ' characters';
    }
  }

  public function numeric($field) {
    if (!is_numeric($this->data[$field])) {
      $this->errors[$field][] = 'The ' . $field . ' field must be a number';
    }
  }

  public function email($field) {
    if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
      $this->errors[$field][] = 'The ' . $field . ' field must be a valid email address';
    }
  }

  // public function unique($field, $table) {
  //   $value = $this->data[$field];
  //   $query = "SELECT * FROM $table WHERE $field = '$value'";
  //   $result = Database::query($query);

  //   if ($result->num_rows > 0) {
  //     $this->errors[$field][] = 'The ' . $field . ' field must be unique';
  //   }
  // }

  public function file($field, $value) {
    if (!isset($_FILES[$field])) {
      $this->errors[$field][] = 'The ' . $field . ' field must be a file';
    }
  }

  public function image($field, $value) {
    $file = $_FILES[$field];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($file['type'], $allowedTypes)) {
      $this->errors[$field][] = 'The ' . $field . ' field must be an image';
    }
  }

  public function min($field, $value) {
    $min = $value;
    $length = strlen($this->data[$field]);

    if ($length < $min) {
      $this->errors[$field][] = 'The ' . $field . ' field must be at least ' . $min . ' characters';
    }
  }

  public function max($field, $value) {
    $max = $value;
    $length = strlen($this->data[$field]);

    if ($length > $max) {
      $this->errors[$field][] = 'The ' . $field . ' field must be at most ' . $max . ' characters';
    }
  }

  public function confirmed($field) {
    $value = $this->data[$field];
    $confirmedValue = $this->data[$field . '_confirmation'];

    if ($value !== $confirmedValue) {
      $this->errors[$field][] = 'The ' . $field . ' field must be confirmed';
    }
  }

  public function mimes($field, $value) {
    $file = $_FILES[$field];
    $allowedTypes = $value;

    if (!in_array($file['type'], $allowedTypes)) {
      $this->errors[$field][] = 'The ' . $field . ' field must be a file of type ' . implode(', ', $allowedTypes);
    }
  }

  public function in($field, $value) {
    $allowedValues = $value;
    $value = $this->data[$field];

    if (!in_array($value, $allowedValues)) {
      $this->errors[$field][] = 'The ' . $field . ' field must be one of the following: ' . implode(', ', $allowedValues);
    }
  }

  public function not_in($field, $value) {
    $disallowedValues = $value;
    $value = $this->data[$field];

    if (in_array($value, $disallowedValues)) {
      $this->errors[$field][] = 'The ' . $field . ' field must not be one of the following: ' . implode(', ', $disallowedValues);
    }
  }

  public function errors() {
    return $this->errors;
  }

  public function validate() {
    foreach ($this->rules as $field => $rules) {
      foreach ($rules as $rule => $value) {
        switch ($rule) {
          case 'required':
            $this->required($field);
            break;
          case 'between':
            $this->between($field, $value);
            break;
          case 'numeric':
            $this->numeric($field);
            break;
          case 'email':
            $this->email($field);
            break;
            // case 'unique':
            //   $this->unique($field, $value);
            //   break;
          case 'file':
            $this->file($field, $value);
            break;
          case 'image':
            $this->image($field, $value);
            break;
          case 'min':
            $this->min($field, $value);
            break;
          case 'max':
            $this->max($field, $value);
            break;
          case 'confirmed':
            $this->confirmed($field);
            break;
          case 'mimes':
            $this->mimes($field, $value);
            break;
          case 'in':
            $this->in($field, $value);
            break;
          case 'not_in':
            $this->not_in($field, $value);
            break;
        }
      }
    }
  }
}
