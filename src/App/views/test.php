<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="/post-req" method="POST">
    <?php

    use MintBerry\Core\Session;

    //csrf_input()
    ?>
    <input type="text" name="name">
    <input type="text" name="price">
    <input type="text" name="description">
    <button type="submit">Submit</button>
  </form>

  <?= Session::get('_csrf_token') ?>
</body>

</html>