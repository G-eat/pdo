<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
    $post = new Post;

    // messages not viewed by users
    $mysqlUsers = 'SELECT DISTINCT your_id FROM chat WHERE user_id = ? AND seen = false';
    $queryUsers = $pdo->prepare($mysqlUsers);
    $queryUsers->execute([$_SESSION['user_id']]);
    $usersMsg = $queryUsers->rowCount();

    if (isset($_POST['category'] , $_POST['title'] ,$_POST['body'])) {
       $category = $_POST['category'];
       $title = $_POST['title'];
       if ($_FILES['image']['name'] !== '') {
         $image_name = $_FILES['image']['name'];
         $image_name_break = explode('.',$image_name);
         $image_name_start = $image_name_break[0];
         $image_name_end = end($image_name_break);
         $file_ext_allowed = array('jpeg','jpg','png','gif');

        if (in_array(strtolower($image_name_end),$file_ext_allowed)) {
          $uniq_id = uniqid('', true);
          $image = $uniq_id . '-' . $image_name_start . '.' . $image_name_end ;
          $file_destination = '../images/'.$image;

          move_uploaded_file($_FILES['image']['tmp_name'],$file_destination);
        } else {
          $image = '';
        }
       } else {
         $image = '';
       }
       $body = nl2br($_POST['body']);
       if ( isset($_SESSION['admin'])) {
         $post_user = $_SESSION['admin'];
       } else {
         $post_user = $_SESSION['log_in'];
       }

       if (empty($category) or empty($title) or empty($body)) {
         $error = 'You Need To Complete Form.';
       } else {
         $mysql = 'INSERT INTO posts (id,post_user,category, title, body, image_file, created_at) VALUES (NULL,?,?,?,?,?, CURRENT_TIMESTAMP)';
         $query = $pdo->prepare($mysql);
         $query->execute([$post_user,$category,$title,$body,$image]);
         header('Location: http://localhost/pdo/client/');
       }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create Post</title>
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
            <li class='active'><a href="#">Create Post</a></li>
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
            <li><a href="http://localhost/pdo/client/create_post.php" class="white-text" style="background:red">Create Post</a></li>
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

      <?php if (isset($error)) {?>
        <div class="container">
          <div class="card-panel red lighten-2">
              <?php echo $error; ?>
          </div>
        </div>
      <?php } ?>

      <br><br>

      <div class="row container animated lightSpeedIn">
       <h4 class="red-text text-darken-4">Create Post</h4>
       <form class="col s12" method='post' enctype="multipart/form-data">
         <div class="row">
           <div class="input-field col s12">
             <input id="category" type="text" class="validate" name='category' value='<?php echo (!empty($_POST['category'])) ?  $_POST['category']:'' ?>'>
             <label for="category">Category</label>
           </div>
         </div>
         <div class="row">
           <div class="input-field col s12">
            <input id="input_text" type="text" data-length="10" class="validate" name='title' value='<?php echo (!empty($_POST['title'])) ?  $_POST['title']:'' ?>'>
            <label for="input_text">Title</label>
          </div>
         </div>
         <div class="row">
           <div class="input-field">
             <textarea id="textarea1" name='body' class="materialize-textarea"><?php echo (!empty($_POST['body'])) ?  $_POST['body']:'' ?></textarea>
             <label for="textarea1">Body</label>
           </div>
           <div class="input-field">
             <input type="file" name="image">
           </div>
           <button name='submit' class="btn waves-effect waves-light" type="submit">Submit</button>
         </div>
       </form>
      </div>

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
<?php } else {
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
} ?>
