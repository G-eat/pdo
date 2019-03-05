<?php
  session_start();

  include_once('../config/connect.php');


  $user_id = $_POST['user_id'];
  $your_id = $_POST['your_id'];
  $mysql = "SELECT * FROM chat WHERE (your_id = ? AND user_id = ?) OR (your_id = ? AND user_id = ?)";
  $query = $pdo->prepare($mysql);
  $query->execute([$your_id,$user_id,$user_id,$your_id]);

  $data = $query->fetchAll();
  echo json_encode($data);

 ?>
