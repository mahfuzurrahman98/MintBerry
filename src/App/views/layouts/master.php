<?php
ob_start(); // Start output buffering
?>
<!DOCTYPE html>
<html>

<head>
  <title>My Website</title>
</head>

<body>
  <h1>My Website</h1>
  <main>
    <?php echo $content; ?>
    <!-- This is where the content will be injected -->
  </main>
</body>

</html>
<?php
ob_end_flush(); // Flush the output buffer
