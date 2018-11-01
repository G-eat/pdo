<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in'])) {
    $post = new Post;

  $posts = $post->get_all();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PDO</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  </head>
  <body>

    <nav>
      <div class="nav-wrapper">
        <div class="container">
          <a href="#" class="brand-logo">Pdo</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li class='active'><a href="#">Posts</a></li>
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/admin.php">Admin</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class='container'>
      <ul class="collection with-header">
        <li class="collection-header"><h6>Posts</h6></li>
        <?php  foreach ($posts as $post) { ?>
          <a href="individualpost.php?id=<?php echo $post['id'] ?>" class="collection-item">
            <?php echo $post['title']; ?>
          </a>
        <?php }?>
      </ul>
    </div>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  </body>
</html>
<?php } else{
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
}?>
