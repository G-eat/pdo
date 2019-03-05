<?php
  session_start();

  include_once('../config/connect.php');

  echo json_encode($_POST);

  $message = $_POST['message'];
  $user_id = $_POST['user_id'];
  $your_id = $_POST['your_id'];
  $mysql = "INSERT INTO chat(id,your_id,user_id, message, created_at) VALUES (NULL,?,?,?, CURRENT_TIMESTAMP)";
  $query = $pdo->prepare($mysql);
  $query->execute([$your_id,$user_id,$message]);

 ?>
