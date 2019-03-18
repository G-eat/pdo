<?php
  session_start();

  include_once('../config/connect.php');

  $user_id = $_POST['user_id'];
  $your_id = $_POST['your_id'];


  $mysql = 'SELECT * FROM chat WHERE user_id = ? AND your_id = ? AND seen = false';
  $query = $pdo->prepare($mysql);
  $query->execute([$your_id,$user_id]);
  $data = $query->rowCount();

  echo $data;


 ?>
