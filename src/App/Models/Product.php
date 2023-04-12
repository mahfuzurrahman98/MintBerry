<?php

namespace MintBerry\App\Models;

use MintBerry\Core\Model;

class Product extends Model {
  protected $table = 'products';
  protected $hiddenColumns = ['deleted_at', 'updated_at'];
  protected $softDelete = true;

  // Define any additional methods specific to the User model here
}
