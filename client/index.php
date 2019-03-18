<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');

  if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
    $post = new Post;

  $posts = $post->get_all();
  $xmlposts = $post->xml_all();


//xml users
  $mysql = "SELECT * FROM users";
  $query = $pdo->prepare($mysql);
  $query->execute();

  $xml = new DOMDocument('1.0','utf-8');
  $xml->formatOutput=true;

  $users = $xml->createElement('users');
  $xml->appendChild($users);

  foreach ( $query->fetchAll() as $user1) {
    $user = $xml->createElement('user');

    $id = $xml->createElement('id',$user1['id']);
    $user->appendChild($id);

    $name = $xml->createElement('name',$user1['name']);
    $user->appendChild($name);

    $admin = $xml->createElement('admin',$user1['admin']);
    $user->appendChild($admin);

    $password = $xml->createElement('password',$user1['password']);
    $user->appendChild($password);

    $created_at = $xml->createElement('created_at',$user1['created_at']);
    $user->appendChild($created_at);

    $users->appendChild($user);
  }



  $xml->save('api\users.xml');
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Posts</title>
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
            <li class='active'><a href="#">Posts</a></li>
            <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
            <li><a href="http://localhost/pdo/client/users.php">Users</a></li>
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
            <li><a href="http://localhost/pdo/client/index.php" class="white-text" style="background:red">Posts</a></li>
            <li><div class="divider"></div></li>
            <li><a href="http://localhost/pdo/client/users.php" class="white-text">Users</a></li>
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

    <div class="container">
      <div class="row">
        <div class="col s12">
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input type="text" id="autocomplete-input" class="autocomplete">
              <label for="autocomplete-input">Search</label>
            </div>
          </div>
        </div>
      </div>
    </div>

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
    <script type="text/javascript">
      let allTitles = {};
      let posts = document.querySelectorAll('.collection-item').forEach((post)=>{
         let post_href = post.href;
         let post_title = post.innerText;
         allTitles[post_title] = post_href;
       });
       document.addEventListener('DOMContentLoaded', function() {
         let elems = document.querySelectorAll('.autocomplete');
         let instances = M.Autocomplete.init(elems, {
           data: allTitles,
           limit : 5,
           onAutocomplete: function() {
             let value = document.querySelector('input.autocomplete').value;
             window.location = allTitles[value];
          },
         });
       });

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
