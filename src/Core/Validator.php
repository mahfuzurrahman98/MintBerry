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

  /*
  // expected format of rules with the attribute name and expected value
  type: int | string | char | array | float | bool | alpha | alpha_num | email
  required: true | false
  between: [min, max]
  length_between: [min, max]
  regex: expression
  unique: [[table, column, except], [table, column, except]]  
  
  */

  /* This is the format how we set a daata to validate
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

private function handleType($attribute, $value, $fieldData) {
  // pass
}

private function handleRequired($attribute, $value, $fieldData) {
  // pass
}

private function handleBetween($attribute, $value, $fieldData) {
  // pass
}

private function handleLengthBetween($attribute, $value, $fieldData) {
  // pass
}

private function handleRegex($attribute, $value, $fieldData) {
  // pass
}

private function handleUnique($attribute, $value, $fieldData) {
  // pass
}

private function handleNumeric($attribute, $value, $fieldData) {
  // pass
}

private function handleAlpha($attribute, $value, $fieldData) {
  // pass
}

private function handleAlphaNum($attribute, $value, $fieldData) {
  // pass
}

private function handleEmail($attribute, $value, $fieldData) {
  

  public function run() {
    echo '<pre>';
    foreach ($this->rules as $field => $constraints) {
      // print_r($constraints);

      // $constraints isa asscociative array consist of attribute as key and 
      // respective values which is another array(with value at first index and custom error message at second index) or any other type
      // if it is not an array then we convert it to array

      echo 'for ' . $field . ':<br>----------<br>';
      foreach ($constraints as $attribute => $value) {
        // echo $attribute .  '<br>';
        if (!is_array($value)) {
          $constraints[$attribute] = [$value, ''];
        }

        print_r($constraints[$attribute]);
      }
      echo '<br><br>';
    }
  }
}
