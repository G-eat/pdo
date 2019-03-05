<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
    $post = new Post;

    if (isset($_POST['category'] , $_POST['title'] ,$_POST['body'])) {
       $category = $_POST['category'];
       $title = $_POST['title'];
       $body = nl2br($_POST['body']);
       $post_user = $_SESSION['log_in'];
       if ( $post_user == '') {
         $post_user = $_SESSION['admin'];
       }

       if (empty($category) or empty($title) or empty($body)) {
         $error = 'You Need To Complete Form.';
       } else {
         $mysql = 'INSERT INTO posts (id,post_user,category, title, body, created_at) VALUES (NULL,?,?,?,?, CURRENT_TIMESTAMP)';
         $query = $pdo->prepare($mysql);
         $query->execute([$post_user,$category,$title,$body]);
         header('Location: http://localhost/pdo/client/');
       }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Pdo | Create Post</title>
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
          <a href="#" class="brand-logo">Pdo</a>
          <ul class="right hide-on-med-and-down">
            <li><a href="http://localhost/pdo/client/">Posts</a></li>
            <li class='active'><a href="#">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/users.php">Users</a></li>
            <?php if (isset($_SESSION['admin'])) { ?>
              <li><a href="http://localhost/pdo/client/admin_dashbord.php">Admin</a></li>
            <?php } else {?>
              <li><a href="http://localhost/pdo/client/admin_logout.php">Log Out</a></li>
            <?php } ?>
          </ul>

          <ul id="nav-mobile" class="sidenav red lighten-2">
            <li><a href="#" class="white-text red darken-4">Pdo</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/index.php" class="white-text">Posts</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/create_post.php" class="white-text" style="background:red">Create Post</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/users.php" class="white-text">Users</a></li>
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

      <div class="row container">
       <h4 class="red-text text-darken-4">Create Post</h4>
       <form class="col s12" method='post'>
         <div class="row">
           <div class="input-field col s12">
             <input id="first_name" type="text" class="validate" name='category'>
             <label for="first_name">Category</label>
           </div>
         </div>
         <div class="row">
           <div class="input-field col s12">
            <input id="input_text" type="text" data-length="10" class="validate" name='title'>
            <label for="input_text">Title</label>
          </div>
         </div>
         <div class="row">
           <div class="input-field">
             <textarea id="textarea1" name='body' class="materialize-textarea"></textarea>
             <label for="textarea1">Body</label>
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
