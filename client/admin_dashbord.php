<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in'])) {
    $post = new Post;
    $posts = $post->get_all();

    $mysql = 'SELECT * FROM users';
    $query = $pdo->prepare($mysql);
    $query->execute();

    $users = $query->fetchAll();

    if (filter_has_var(INPUT_POST,'submit')) {
      $id = $_POST["id"];
      $mysql = 'DELETE FROM posts WHERE id = ?';
      $query = $pdo->prepare($mysql);
      $query->execute([$id]);
      header('Location: http://localhost/pdo/client/admin_dashbord.php');
      }

      if (filter_has_var(INPUT_POST,'submit_user')) {
        $name = $_POST["name"];
        $mysql = 'DELETE FROM users WHERE name = ?';
        $query = $pdo->prepare($mysql);
        $query->execute([$name]);
        header('Location: http://localhost/pdo/client/admin_dashbord.php');
        }
?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PDO | Dashbord</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  </head>
  <body>

    <nav>
      <div class="nav-wrapper">
        <div class="container">
          <a href="#" class="brand-logo">Pdo</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="http://localhost/pdo/client/index.php">Posts</a></li>
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/admin_logout.php">Log Out</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <br><br>

    <div class='container'>
      <ul class="collection with-header red-text text-darken-4">
        <li class="collection-header"><h6>Posts</h6></li>
        <?php  foreach ($posts as $post) { ?>
          <a href="individualpost.php?id=<?php echo $post['id'] ?>" class="collection-item">
            <?php echo $post['title']; ?>
            <span style="float:right">
              <form method="post">
                <input type="hidden" name='id' value="<?php echo $post['id'] ?>">
                <input class="red lighten-2" type="submit" name="submit" value="DELETE &#10006;" />
              </form>
            </span>
          </a>
        <?php }?>
      </ul>
      <hr>
    </div>

    <br>

    <div class='container'>
      <ul class="collection with-header">
        <li class="collection-header red-text text-darken-4"><h6>Users</h6></li>
        <?php  foreach ($users as $user) { ?>
          <li class="collection-item">
            <?php echo $user['name']; ?>
            <span style="float:right">
              <form method="post">
                <input type="hidden" name='name' value="<?php echo $user['name'] ?>">
                <input class="red lighten-2" type="submit" name="submit_user" value="DELETE &#10006;" />
              </form>
            </span>
          </li>
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
