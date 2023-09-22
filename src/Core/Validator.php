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

  public function hasErrors() {
    return count($this->errors) > 0;
  }

  public function getErrors() {
    return $this->errors;
  }

  private function isInt($value): bool {
    return is_int($value);
  }

  private function isString($value): bool {
    return is_string($value);
  }

  private function isChar($value): bool {
    return is_string($value) && strlen($value) == 1;
  }

  private function isArray($value): bool {
    return is_array($value);
  }

  private function isFloat($value): bool {
    return is_float($value);
  }

  private function isNumeric($value): bool {
    return is_numeric($value);
  }

  private function isBool($value): bool {
    return is_bool($value);
  }

  private function isAlpha($value): bool {
    return ctype_alpha($value);
  }

  private function isAlphaNum($value): bool {
    return ctype_alnum($value);
  }

  private function isEmail($value): bool {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  private function isType($type, $value): bool {
    if ($type == 'int') {
      return $this->isInt($value);
    } else if ($type == 'string') {
      return $this->isString($value);
    } else if ($type == 'char') {
      return $this->isChar($value);
    } else if ($type == 'array') {
      return $this->isArray($value);
    } else if ($type == 'float') {
      return $this->isFloat($value);
    } else if ($type == 'numeric') {
      return $this->isNumeric($value);
    } else if ($type == 'bool') {
      return $this->isBool($value);
    } else if ($type == 'alpha') {
      return $this->isAlpha($value);
    } else if ($type == 'alpha_num') {
      return $this->isAlphaNum($value);
    } else if ($type == 'email') {
      return $this->isEmail($value);
    }

    return false;
  }

  private function handleType($constraints, $field): string {
    // check the $constraints[0] is in the available types
    if (!is_string($constraints[0]) || !in_array($constraints[0], $this->availableTypes)) {
      return 'The type is not supported.';
    }

    if (isset($constraints[1]) && is_string($constraints[1])) {
      $errorMsg = $constraints[1];
    } else {
      $errorMsg = 'The field must be of type ' . $constraints[0] . '.';
    }

    if (property_exists($this->data, $field)) {
      $fieldData = $this->data->$field;
      if (!$this->isType($constraints[0], $fieldData)) {
        return $errorMsg;
      }
    }

    return '';
  }

  private function handleRequired($constraints, $field): string {
    if (isset($constraints[1]) && is_string($constraints[1])) {
      $errorMsg = $constraints[1];
    } else {
      $errorMsg = 'The field is required.';
    }

    if ($constraints[0] === true) {
      if (!isset($this->data->$field)) {
        return $errorMsg;
      }

      $fieldData = $this->data->$field;
      if (!isset($fieldData) || empty($fieldData)) {
        return $errorMsg;
      }
    }

    return '';
  }

  private function handleBetween($constraints, $field): string {
    if (isset($constraints[1]) && is_string($constraints[1])) {
      $errorMsg = $constraints[1];
    }

    // first check the $constraints[0] is a string and has value
    if (!isset($constraints[0]) || empty($constraints[0])) {
      return $errorMsg ?? 'Provide the min and max in comma separated format.';
    }
    // trim the value
    $value = trim($constraints[0]);

    // now make sure the value is in the format of min,max
    $value = explode(',', $value);

    if (count($value) != 2) {
      return $errorMsg ?? 'Provide the min and max in comma separated format.';
    }

    // if the value is not an integer then throw an error
    if (!is_numeric($value[0]) || !is_numeric($value[1])) {
      return $errorMsg ?? 'The min and max must be integers.';
    }

    // if the min is greater than max then throw an error
    if ($value[0] > $value[1]) {
      return $errorMsg ?? 'The min value must be less than max value.';
    }

    // now check the value of the field is between the min and max
    if (property_exists($this->data, $field)) {
      $fieldData = $this->data->$field;
      // the field data must be numeric
      if (!is_numeric($fieldData)) {
        return $errorMsg ?? 'The field value must be numeric.';
      }
      if ($fieldData < $value[0] || $fieldData > $value[1]) {
        return $errorMsg ?? 'The field value must be between ' . $value[0] . ' and ' . $value[1] . '.';
      }
    }
    return '';
  }

  private function handleLengthBetween($constraints, $field): string {
    if (isset($constraints[1]) && is_string($constraints[1])) {
      $errorMsg = $constraints[1];
    }

    // first check the $constraints[0] is a string and has value
    if (!isset($constraints[0]) || empty($constraints[0])) {
      return $errorMsg ?? 'Provide the min and max in comma separated format.';
    }
    // trim the value
    $value = trim($constraints[0]);

    // now make sure the value is in the format of min,max
    $value = explode(',', $value);

    if (count($value) != 2) {
      return $errorMsg ?? 'Provide the min and max in comma separated format.';
    }

    // if the value is not an integer then throw an error
    if (!is_numeric($value[0]) || !is_numeric($value[1])) {
      return $errorMsg ?? 'The min and max must be integers.';
    }

    // if the min is greater than max then throw an error
    if ($value[0] > $value[1]) {
      return $errorMsg ?? 'The min value must be less than max value.';
    }

    // now check the value of the field is between the min and max
    if (property_exists($this->data, $field)) {
      $fieldData = $this->data->$field;
      // the field data must be string
      if (!is_string($fieldData)) {
        return $errorMsg ?? 'The field value must be string.';
      }
      if (strlen($fieldData) < $value[0] || strlen($fieldData) > $value[1]) {
        return $errorMsg ?? 'The field length must be between ' . $value[0] . ' and ' . $value[1] . '.';
      }
    }
    return '';
  }

  private function handleRegex($constraints, $field): string {
    if (isset($constraints[1]) && is_string($constraints[1])) {
      $errorMsg = $constraints[1];
    }

    // first check the $constraints[0] is a string and has value
    if (!isset($constraints[0]) || empty($constraints[0])) {
      return $errorMsg ?? 'Provide the regular expression.';
    }

    // trim the value and store it in a variable
    $pattern = trim($constraints[0]);

    if (property_exists($this->data, $field)) {
      $fieldData = $this->data->$field;
      // the field data must be string
      if (!is_string($fieldData)) {
        return $errorMsg ?? 'The field value must be string.';
      }

      // now check the field value matches the regular expression
      if (!preg_match($pattern, $fieldData)) {
        return $errorMsg ?? 'The field value must match the regular expression.';
      }
    }

    return '';
  }

  private function handleUnique($constraints, $field) {
    // pass
  }

  private function handleAttributeValidation($attribute, $constraints, $field): string {
    if ($attribute == 'required') {
      return $this->handleRequired($constraints, $field);
    } else if ($attribute == 'type') {
      return $this->handleType($constraints, $field);
    } else if ($attribute == 'between') {
      return $this->handleBetween($constraints, $field);
    } else if ($attribute == 'length_between') {
      return $this->handleLengthBetween($constraints, $field);
    } else if ($attribute == 'regex') {
      $this->handleRegex($constraints, $field);
    }
    // else if ($attribute == 'unique') {
    //   $this->handleUnique($constraints, $field);
    // }

    return '';
  }

  public function run(): void {
    foreach ($this->rules as $field => $constraints) {

      // make sure the required constraint is always first
      if (isset($constraints['required'])) {
        $requiredElement = $constraints['required'];
        unset($constraints['required']);
        $constraints = array_merge(['required' => $requiredElement], $constraints);
      }

      foreach ($constraints as $attribute => $_) {
        if (is_array($constraints[$attribute])) {
          // it should be an array of 2 elements
          if (count($constraints[$attribute]) != 2) {
            if (!isset($this->errors[$field])) {
              $this->errors[$field] = "Invalid format of constraints.";
            }
            return;
          }
        } else {
          $constraints[$attribute] = [$constraints[$attribute], null];
        }

        // the first element must be a string or boolean
        if (!is_string($constraints[$attribute][0]) && !is_bool($constraints[$attribute][0])) {
          if (!isset($this->errors[$field])) {
            $this->errors[$field] = "Invalid format of constraints.";
          }
          return;
        }

        // the second element must be a string or null
        if (!is_string($constraints[$attribute][1]) && !is_null($constraints[$attribute][1])) {
          if (!isset($this->errors[$field])) {
            $this->errors[$field] = "Invalid format of constraints.";
          }
          return;
        }

        $errorMsg = $this->handleAttributeValidation(
          $attribute,
          $constraints[$attribute],
          $field
        );

        if ($errorMsg) {
          if (!isset($this->errors[$field])) {
            $this->errors[$field] = $errorMsg;
          }
        }
      }
    }
  }
}
