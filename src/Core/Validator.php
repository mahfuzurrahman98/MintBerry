<?php

namespace MintBerry\Core;

class Validator {
  private $errors = [];
  private $rules = [];
  private $data = [];
  private $availableTypes = [
    'int',
    'string',
    'char',
    'array',
    'float',
    'numeric',
    'bool',
    'alpha',
    'alpha_num',
    'email',
  ];

  public function __construct($data, $rules) {
    $this->data = $data;
    $this->rules = $rules;
  }

  /*
  // expected format of rules with the attribute name and expected value
  type: int | string | char | array | float | bool | alpha | alpha_num | email
  required: true | false
  between: [min, max]
  length_between: [min, max]
  regex: expression
  unique: [[table, column, except], [table, column, except]]  
  
  This is the format how we set a daata to validate

  $rules = [
    'sku' => [
      'type' => ['alpha_num', 'The SKU must be alphanumeric.']
      'required' => true,
      'between' => [3, 255],
    ],
    'name' => [
      'type' => 'string',
      'unique' => ['products', 'name'],
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

  public function hasErrors() {
    return count($this->errors) > 0;
  }

  public function getErrors() {
    return $this->errors;
  }

  private function handleType($constraints, $field) {
    // pass
  }

  private function handleRequired($constraints, $field) {
    if (!isset($this->data->$field)) {
      $this->errors[$field] = $constraints[1] ?? 'The field is required.';
      return;
    }

    $fieldData = $this->data->$field;
    $errorMsg = $constraints[1] ?? 'The field is required.';
    if (!isset($fieldData) || empty($fieldData)) {
      $this->errors[$field] = $errorMsg;
    }
  }

  private function handleBetween($constraints, $field) {
    /*
    a sample data is of $constraints
    array(2) {
  [0]=>
  string(5) "3,255"
  [1]=>
  NULL or a message
}
    */

    // first check the $constraints[0] is a string and has value
    if (!isset($constraints[0]) || empty($constraints[0])) {
      $this->errors[$field] = 'Provide the min and max in comma separated format.';
      return;
    }
    // trim the value
    $value = trim($constraints[0]);
    // now make sure the value is in the format of min,max they are integers and min < max and it is exactly 2 values comma separated
    $value = explode(',', $value);
    if (count($value) != 2) {
      $this->errors[$field] = 'Provide the min and max in comma separated format.';
      return;
    }

    // if the value is not an integer then throw an error
    if (!is_numeric($value[0]) || !is_numeric($value[1])) {
      $this->errors[$field] = 'Provide the min and max in comma separated format.';
      return;
    }

    // if the min is greater than max then throw an error
    if ($value[0] > $value[1]) {
      $this->errors[$field] = 'The min value must be less than max value.';
      return;
    }

    // now check the value of the field is between the min and max
    $fieldData = $this->data->$field;
    if (!isset($fieldData) || empty($fieldData)) {
      $this->errors[$field] = 'The field is required.';
      return;
    }
  }

  private function handleLengthBetween($constraints, $field) {
    // pass
  }

  private function handleRegex($constraints, $field) {
    // pass
  }

  private function handleUnique($constraints, $field) {
    // pass
  }

  private function handleAttributeValidation($attribute, $constraints, $field) {
    if ($attribute == 'type') {
      $this->handleType($constraints, $field);
    } else  if ($attribute == 'required') {
      $this->handleRequired($constraints, $field);
    } else if ($attribute == 'between') {
      $this->handleBetween($constraints, $field);
    } else if ($attribute == 'length_between') {
      $this->handleLengthBetween($constraints, $field);
    } else if ($attribute == 'regex') {
      $this->handleRegex($constraints, $field);
    }
    // else if ($attribute == 'unique') {
    //   $this->handleUnique($constraints, $field);
    // }
  }

  public function run() {
    foreach ($this->rules as $field => $constraints) {

      // make sure the required constraint is always first
      if (isset($constraints['required'])) {
        $requiredElement = $constraints['required'];
        unset($constraints['required']);
        $constraints = array_merge(['required' => $requiredElement], $constraints);
      }

      foreach ($constraints as $attribute => $_) {
        if (!is_array($constraints[$attribute])) {
          $constraints[$attribute] = [$constraints[$attribute], null];
        }

        $this->handleAttributeValidation(
          $attribute,
          $constraints[$attribute],
          $field
        );
      }
    }
  }
}
