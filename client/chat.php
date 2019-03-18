<?php
  session_start();

  include_once('../config/connect.php');

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
    $id = $_GET['id'];

    $mysql = 'SELECT * FROM users WHERE id = ?';
    $query = $pdo->prepare($mysql);
    $query->execute([$id]);

    $user = $query->fetch();

    if ($user == false) {
      header('Location: http://localhost/pdo/client/404.php');
    }else{

      if (isset($_POST['message'])) {
        echo $_POST['message'];
      }
 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <!--Let browser know website is optimized for mobile-->
     <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
     <title>Chat <?php echo $user['name'] ?></title>
     <!-- Compiled and minified CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
     <link rel="stylesheet" href="./chat.css">
   </head>
   <body>
     <div class="wrapper">
      <nav class="nav" id="nav">
        <div class="default-nav">
          <div class="main-nav">
            <a href="http://localhost/pdo/client/users.php" class="toggle"></a>
            <div class="main-nav-item"><a class="main-nav-item-link" href="#"><?php echo $user['name'] ?></a></div>
            <div class="options"></div>
          </div>
        </div>
      </nav>
      <div class="inner" id="inner">
        <div class="content" id="content"></div>
      </div>
      <div class="bottom" id="bottom">
        <textarea onKeyDown="if(event.keyCode==13 ) get_message();" class="input" id="input" required></textarea>
        <input type="hidden" id="user_id" value=<?php echo $_GET['id'] ?>>
        <input type="hidden" id="your_id" value=<?php echo $_SESSION['user_id'] ?>>
        <button class="send" onclick="get_message()" id="send"></button>
      </div>
    </div>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" charset="utf-8"></script> -->
    <script src="./chat.js"></script>
   </body>
 </html>
<?php }
} else {
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
} ?>
