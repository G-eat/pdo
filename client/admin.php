<?php
  session_start();

  include_once('../config/connect.php');

  if (isset($_SESSION['log_in'])) {
    header('Location: http://localhost/pdo/client/admin_dashbord.php');
    exit();
  } else {
      if (isset($_POST['first_name'] , $_POST['last_name'] , $_POST['password'])) {
        $name = $_POST['first_name']. ' ' . $_POST['last_name'];
        $password = md5($_POST['password']);

        if (empty($name) || empty($password)) {
          $error = 'Complete Form.';
        }else{
          $mysql = 'SELECT * FROM users WHERE name = ? AND password = ?';
          $query = $pdo->prepare($mysql);
          $query->execute([$name,$password]);

          $num = $query->rowCount();

          $mysql2 = 'SELECT * FROM users WHERE name = ? AND password = ? AND admin =1';
          $query2 = $pdo->prepare($mysql2);
          $query2->execute([$name,$password]);

          $num2 = $query2->rowCount();

          if ($num == 1) {
            if ($num2 == 1) {
              $_SESSION['admin'] = $name;
              header('Location: http://localhost/pdo/client/admin_dashbord.php');
              exit();
            } else{
              $_SESSION['log_in'] = $name;
              header('Location: http://localhost/pdo/client/index.php');
              exit();
            }
          } else {
            $error = 'Not Correct !';
          }
      }
    }

    ?>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title>PDO | Login</title>
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
              <ul class="right hide-on-med-and-down">
                <?php if (isset($_SESSION['log_in'])) { ?>
                  <li><a href="http://localhost/pdo/client/index.php">Posts</a></li>
                  <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
                  <li class='active'><a href="http://localhost/pdo/client/admin.php">Admin</a></li>
                <?php } ?>
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
         <h4 class="red-text text-darken-4">Login</h4>
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
         <h6 class="red-text text-darken-4"><small>Don't have account? </small><a href="http://localhost/pdo/client/admin_register.php">Register now.</a></h6>
        </div>

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      </body>
    </html>
<?php } ?>
