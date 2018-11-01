<?php
  session_start();

  session_destroy();

  header('Location: http://localhost/pdo/client/admin.php');

?>
