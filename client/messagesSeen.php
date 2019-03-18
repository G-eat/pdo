<?php
  session_start();

  include_once('../config/connect.php');


  $user_id = $_POST['user_id'];
  $your_id = $_POST['your_id'];

  $mysql1 = "UPDATE `chat` SET `seen`=true WHERE user_id = ? AND your_id = ?";
  $query1 = $pdo->prepare($mysql1);
  $query1->execute([$your_id,$user_id]);
  $data = $query->rowCount();

  echo json_encode($data);
 ?>
