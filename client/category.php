<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');


  if (isset($_GET['name'])) {
    $mysql = 'SELECT * FROM posts WHERE category = ?';
    $query = $pdo->prepare($mysql);
    $query->execute([$_GET['name']]);
    $categories = $query->fetchAll();
    $num = $query->rowCount();
    if ($num <= 0) {
      $error = 'Error!!.Not right link.';
    }
  }else{
    header('Location: index.php');
    exit();
  }

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
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
      <title>{}</title>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <!-- Compiled and minified CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!-- animate css -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
      <!-- my css -->
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
              <li><a href="http://localhost/pdo/client/">Posts</a></li>
              <li><a href="create_post.php">Create Post</a></li>
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
              <li><a href="http://localhost/pdo/client/create_post.php" class="white-text">Create Post</a></li>
              <li><div class="divider"></div></li>
              <li><a href="http://localhost/pdo/client/users.php" class="white-text">Users <?php if($usersMsg){ echo'<span class="red accent-3 black-text circle" style="padding:0 0.6rem">'.$usersMsg.'</span>';} ?></a></li>
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

      <br>

      <?php if (isset($error)){ ?>
        <div class="container animated heartBeat">
          <div class="card-panel red lighten-2">
              <?php echo $error; ?>
          </div>
        </div>
      <?php } else { ?>
        <div class='container'>
          <ul class="collection with-header">
            <li class="collection-header"><h6 class="blue-text darken-4">Posts of category : <span style="color:red"><?php echo $_GET['name'] ?></span></h6></li>
            <?php  foreach ($categories as $category) { ?>
              <div class="card  light-green lighten-5">
                <div class="card-content white-text">
                  <span class="card-title center-align"><a href='http://localhost/pdo/client/individualpost.php?id=<?php echo $category['id'] ?>'><h5 class='amber-text text-darken-2'><?php echo $category['title'] ?></h5></a></span><hr><br>
                  <?php $result = substr($category['body'], 0, 300); ?>
                  <p class="grey-text"><?php echo $result . '...' ?></p>
                  <br>
                  <a href="http://localhost/pdo/client/individualpost.php?id=<?php echo $category['id'] ?>" class="btn waves-effect waves-light">See More</a>
                </div>
                <div class="card-action">
                  <span>Created_at <span class='amber-text'><?php echo $category['created_at'] ?></span> by : <span class='amber-text'><a href='profile.php?user=<?php echo $category['post_user'] ?>'><?php echo $category['post_user'] ?></span></a></span></span>
                </div>
                <br><br>
              </div>
            <?php }?>
          </ul>
        </div>
      <?php } ?>


      <!-- Compiled and minified JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          let elems = document.querySelectorAll('.sidenav');
          let instances = M.Sidenav.init(elems, {draggable:true});
        });
      </script>
    </body>
  </html>
  <?php
  }else{
    echo 1;
  }
?>
