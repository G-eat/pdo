<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in'])) {
    $post = new Post;

    if (isset($_POST['first_name'] , $_POST['last_name'] , $_POST['title'] ,$_POST['body'])) {
       $name = $_POST['first_name']. ' ' . $_POST['last_name'];
       $title = $_POST['title'];
       $body = nl2br($_POST['body']);

       if (empty($name) or empty($title) or empty($body)) {
         $error = 'You Need To Complete Form.';
       } else {
         $mysql = 'INSERT INTO posts (id, author, title, body, created_at) VALUES (NULL,?,?,?, CURRENT_TIMESTAMP)';
         $query = $pdo->prepare($mysql);
         $query->execute([$name,$title,$body]);
         header('Location: http://localhost/pdo/client/');
       }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Pdo | Create Post</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  </head>
  <body>

    <nav>
      <div class="nav-wrapper">
        <div class="container">
          <a href="#" class="brand-logo">Pdo</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="http://localhost/pdo/client/">Posts</a></li>
            <li class='active'><a href="#">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/admin.php">Admin</a></li>
          </ul>
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
           <div class="input-field col s6">
             <input id="first_name" type="text" class="validate" name='first_name'>
             <label for="first_name">First Name</label>
           </div>
           <div class="input-field col s6">
             <input id="last_name" type="text" class="validate" name='last_name'>
             <label for="last_name">Last Name</label>
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
  </body>
</html>
<?php } else {
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
} ?>
