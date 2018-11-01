<?php

try {
  $pdo = new PDO('mysql:host=localhost;dbname=pdo','root','');
} catch (PDOException $e) {
  exit('Database Error');
}
 ?>
