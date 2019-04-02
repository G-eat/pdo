<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
    $user = $_GET['user'];
    $mysql = 'SELECT * FROM posts WHERE post_user=?';
    $query = $pdo->prepare($mysql);
    $query->execute([$user]);

    $posts = $query->fetchAll();

    $mysql1 = 'SELECT * FROM users WHERE name=?';
    $query1 = $pdo->prepare($mysql1);
    $query1->execute([$user]);

    $user = $query1->fetch();

    // messages not viewed by users
    $mysqlUsers = 'SELECT DISTINCT your_id FROM chat WHERE user_id = ? AND seen = false';
    $queryUsers = $pdo->prepare($mysqlUsers);
    $queryUsers->execute([$_SESSION['user_id']]);
    $usersMsg = $queryUsers->rowCount();

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $_GET['user'] ?>'s Profile</title>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>

    <nav>
      <div class="nav-wrapper">
        <div class="container">
          <?php if (isset($_SESSION['admin'])) { ?>
            <a href="http://localhost/pdo/client/profile.php?user=<?php echo $_SESSION['admin'] ?>" class="brand-logo"><?php echo $_SESSION['admin'] ?></a>
          <?php } else {?>
            <a href="http://localhost/pdo/client/profile.php?user=<?php echo $_SESSION['log_in'] ?>" class="brand-logo"><?php echo $_SESSION['log_in'] ?></a>
          <?php } ?>
          <ul class="right hide-on-med-and-down">
            <li><a href="http://localhost/pdo/client/index.php">Posts</a></li>
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/users.php">Users <?php if($usersMsg){ echo'<span class="red accent-3 black-text circle" style="padding:0 0.6rem">'.$usersMsg.'</span>';} ?></a></li>
            <?php if (isset($_SESSION['admin'])) { ?>
              <li><a href="http://localhost/pdo/client/admin_dashbord.php">Admin</a></li>
            <?php } else {?>
              <li><a href="http://localhost/pdo/client/admin_logout.php">Log Out</a></li>
            <?php } ?>
          </ul>

          <ul id="nav-mobile" class="sidenav red lighten-2">
            <?php if (isset($_SESSION['admin'])) { ?>
              <li><a href="http://localhost/pdo/client/profile.php?user=<?php echo $_SESSION['admin'] ?>" class="white-text red darken-4"><?php echo $_SESSION['admin'] ?></a></li>
            <?php } else {?>
              <li><a href="http://localhost/pdo/client/profile.php?user=<?php echo $_SESSION['log_in'] ?>" class="white-text red darken-4"><?php echo $_SESSION['log_in'] ?></a></li>
            <?php } ?>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/index.php" class="white-text">Posts</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/users.php" class="white-text">Users <?php if($usersMsg){ echo'<span class="red accent-3 black-text circle" style="padding:0 0.6rem">'.$usersMsg.'</span>';} ?></a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/create_post.php" class="white-text">Create Post</a></li>
            <li><div class="divider"></div></li>
            <?php if (isset($_SESSION['admin'])) { ?>
              <li><a href="http://localhost/pdo/client/admin_dashbord.php" class="white-text">Admin</a></li>
              <li><div class="divider"></div></li>
            <?php }?>
            <li><a href="http://localhost/pdo/client/admin_logout.php" class="white-text">Log Out</a></li>
            <li><div class="divider"></div></li>
          </ul>
          <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
      </div>
    </nav>

    <br><br>

    <div class='container'>
      <ul class="collection with-header">
        <li class="collection-header"><h6 class="blue-text darken-4">Posts of <?php echo $_GET['user'] ?><span style="float:right;"><a href='chat.php?id=<?php echo $user['id'] ?>'><i class="material-icons">chat</i>with <?php echo $_GET['user'] ?></span></a></h6></li>
        <?php  foreach ($posts as $post) { ?>
          <div class="card  light-green lighten-5">
            <div class="card-content white-text">
              <span class="card-title center-align"><a href='http://localhost/pdo/client/individualpost.php?id=<?php echo $post['id'] ?>'><h5 class='amber-text text-darken-2'><?php echo $post['title'] ?></h5></a></span><hr><br>
              <p class="grey-text"><?php echo $post['body'] ?></p>
            </div>
            <div class="card-action">
              <span>Created_at <span class='amber-text'><?php echo $post['created_at'] ?></span> in category : <span class='amber-text'><?php echo $post['category'] ?></span></span>
            </div>
            <br><br>
          </div>
        <?php }?>
      </ul>
    </div>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="text/javascript">
       document.addEventListener('DOMContentLoaded', function() {
         let elems = document.querySelectorAll('.sidenav');
         let instances = M.Sidenav.init(elems, {draggable:true});
       });
    </script>
  </body>
</html>
<?php } else{
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
}?>
