<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $code ?> Error</title>
  <style>
    body {
      background-color: #f4eeee;
      font-family: Arial, sans-serif;
      margin: 0;
    }

    .container {
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    p {
      color: #8e8e8e;
      font-size: 25px;
    }
  </style>
</head>

<body>
  <div class="container">
    <p><?= $code . ' | ' . $errorMessages[$code] ?></p>
  </div>
</body>

</html>