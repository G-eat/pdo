<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['admin'])) {
    $post = new Post;
    $posts = $post->get_all();

    $mysql = 'SELECT * FROM users';
    $query = $pdo->prepare($mysql);
    $query->execute();

    $users = $query->fetchAll();

    $mysql_report = 'SELECT * FROM report';
    $query_report = $pdo->prepare($mysql_report);
    $query_report->execute();

    $reports = $query_report->fetchAll();

    if (filter_has_var(INPUT_POST,'submit')) {
      $id = $_POST["id"];
      $mysql = 'DELETE FROM posts WHERE id = ?';
      $query = $pdo->prepare($mysql);
      $query->execute([$id]);

      $mysql_delComments = 'DELETE FROM comments WHERE post_id = ?';
      $query_delComments = $pdo->prepare($mysql_delComments);
      $query_delComments->execute([$id]);

      $mysql_delReports = 'DELETE FROM report WHERE post_id = ?';
      $query_delReports = $pdo->prepare($mysql_delReports);
      $query_delReports->execute([$id]);
      header('Location: http://localhost/pdo/client/admin_dashbord.php');
      }

      if (filter_has_var(INPUT_POST,'submit_user')) {
        $name = $_POST["name"];
        $mysql = 'DELETE FROM users WHERE name = ?';
        $query = $pdo->prepare($mysql);
        $query->execute([$name]);
        header('Location: http://localhost/pdo/client/admin_dashbord.php');
        }

        if (filter_has_var(INPUT_POST,'delete_report')) {
          $id = $_POST["report"];
          $mysql = 'DELETE FROM report WHERE id = ?';
          $query = $pdo->prepare($mysql);
          $query->execute([$id]);
          header('Location: http://localhost/pdo/client/admin_dashbord.php');
          }
?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PDO | Dashbord</title>
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
            <li><a href="http://localhost/pdo/client/index.php">Posts</a></li>
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/admin_logout.php">Log Out</a></li>
          </ul>

          <ul id="nav-mobile" class="sidenav red lighten-2">
            <li><a href="#" class="white-text red darken-4">Pdo</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/index.php" class="white-text">Posts</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/create_post.php" class="white-text">Create Post</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/admin_logout.php" class="white-text">Log Out</a></li>
            <li><div class="divider"></div></li>
          </ul>
          <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
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

    <div class='container'>
      <ul class="collection with-header">
        <li class="collection-header red-text text-darken-4"><h6>Reports</h6></li>
        <?php  foreach ($reports as $report) { ?>
          <li class="collection-item">
            <a href="individualpost.php?id=<?php echo $report['post_id']?>"><?php echo $report['report']; ?></a>
            <span style="float:right">
              <form method="post">
                <input type="hidden" name='report' value="<?php echo $report['id'] ?>">
                <input class="red lighten-2" type="submit" name="delete_report" value="DELETE &#10006;" />
              </form>
            </span>
          </li>
        <?php }?>
      </ul>
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

<?php } elseif (isset($_SESSION['log_in'])) {
    header('Location: http://localhost/pdo/client/index.php');
    exit();
  }else{
   header('Location: http://localhost/pdo/client/admin.php');
   exit();
}?>
