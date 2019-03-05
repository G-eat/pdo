<?php
  include_once('../config/connect.php');

  if (isset($_POST['first_name'] , $_POST['last_name'] , $_POST['password'])) {
    $name = $_POST['first_name']. ' ' . $_POST['last_name'];
    $originalPass = $_POST['password'];
    $password = md5($_POST['password']);

    if (strlen($originalPass) < 8) {
      $error = 'Password must have 8 characters.';
    } else {
      $mysql = 'SELECT * FROM users WHERE name = ?';
      $query = $pdo->prepare($mysql);
      $query->execute([$name]);

      $num = $query->rowCount();

      if ($num == 1) {
        $error = 'This username is in use.';
      }else{
        $mysql = 'INSERT INTO users (name, password, created_at) VALUES (?,?, CURRENT_TIMESTAMP)';
        $query = $pdo->prepare($mysql);
        $query->execute([$name,$password]);

        header('Location: http://localhost/pdo/client/admin.php');
        exit();
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PDO | Register</title>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li class='active'><a href="http://localhost/pdo/client/admin.php">Admin</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <?php if (isset($error)) {?>
      <div class="container">
        <div class="card-panel red lighten-2">
            <?php echo $error; ?>
        </div>
      </div>
    <?php } ?>

    <br><br>

    <div class="row container">
     <h4 class="red-text text-darken-4">Register</h4>
     <form class="col s12" method='post'>
       <div class="row">
         <div class="input-field col s6">
           <input id="first_name" type="text" class="validate" name='first_name' required>
           <label for="first_name">First Name</label>
         </div>
         <div class="input-field col s6">
           <input id="last_name" type="text" class="validate" name='last_name'>
           <label for="last_name">Last Name</label>
         </div>
         <div class="input-field col s12">
          <input id="password" type="password" class="validate" name='password' required>
          <label for="password">Password</label>
        </div>
         <button name='submit' class="btn waves-effect waves-light" type="submit">Submit</button>
       </div>
     </form>
     <h6 class="red-text text-darken-4"><small>Have account? </small><a href="http://localhost/pdo/client/admin.php">Go back.</a></h6>
    </div>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  </body>
</html>
