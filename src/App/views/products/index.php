<?php
$products = [
  "Product 1",
  "Product 2",
  "Product 3",
];
ob_start(); // Start output buffering
?>

<?php section('content', { ?>
<ul>
  <?php foreach ($products as $product) { ?>
    <li><?php echo $product; ?></li>
  <?php } ?>
</ul>

<?php }) ?>