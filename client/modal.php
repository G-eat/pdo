<?php
  include_once('../config/connect.php');

  // get report from form
  if (filter_has_var(INPUT_POST,'submit')) {
    $report = htmlspecialchars($_POST["report"]);
    if(empty($report)){
      alert('Fill the field');
    }else{
      $id = $_POST['post_id'];
      $report = htmlspecialchars($_POST["report"]);
      $mysql = 'INSERT INTO report (id, post_id, report, created_at) VALUES (NULL,?,?, CURRENT_TIMESTAMP)';
      $query = $pdo->prepare($mysql);
      $query->execute([$id,$report]);
      header('Location: http://localhost/pdo/client/individualpost.php?id='.$id);
    }
  }

 ?>
