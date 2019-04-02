<?php
session_start();

include_once('../config/connect.php');

if (isset($_SESSION['log_in']) || isset($_SESSION['admin'])) {
  if (isset($_SESSION['admin'])) {
    $name = $_SESSION['admin'];
  }else {
    $name = $_SESSION['log_in'];
  }

  $mysql = 'SELECT * FROM users WHERE name != ?';
  $query = $pdo->prepare($mysql);
  $query->execute([$name]);

  $users = $query->fetchAll();

  $mysql1 = 'SELECT * FROM users WHERE name = ?';
  $query1 = $pdo->prepare($mysql1);
  $query1->execute([$name]);

  $you = $query1->fetch();

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
     <title>Users</title>
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
           <a href="http://localhost/pdo/client/profile.php?user=<?php echo $name ?>" class="brand-logo"><?php echo $name ?></a>
           <ul class="right hide-on-med-and-down">
             <li ><a href="http://localhost/pdo/client/index.php">Posts</a></li>
             <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
             <li class="active"><a href="http://localhost/pdo/client/users.php">Users <?php if($usersMsg){ echo'<span class="red accent-3 black-text circle" style="padding:0 0.6rem">'.$usersMsg.'</span>';} ?></a></li>
             <?php if (isset($_SESSION['admin'])) { ?>
               <li><a href="http://localhost/pdo/client/admin_dashbord.php">Admin</a></li>
             <?php } else {?>
               <li><a href="http://localhost/pdo/client/admin_logout.php">Log Out</a></li>
             <?php } ?>
           </ul>

           <ul id="nav-mobile" class="sidenav red lighten-2">
             <li><a href="http://localhost/pdo/client/profile.php?user=<?php echo $name ?>" class="white-text red darken-4"><?php echo $name ?></a></li>
             <li><div class="divider"></div></li>
             <li><a href="http://localhost/pdo/client/index.php" class="white-text">Posts</a></li>
             <li><div class="divider"></div></li>
             <li><a href="http://localhost/pdo/client/create_post.php" class="white-text">Create Post</a></li>
             <li><div class="divider"></div></li>
             <li><a href="http://localhost/pdo/client/users.php" class="white-text" style="background:red">Users <?php if($usersMsg){ echo'<span class="red accent-3 black-text circle" style="padding:0 0.6rem">'.$usersMsg.'</span>';} ?></a></li>
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

     <div class="container animated zoomIn">
       <ul class="collection with-header">
         <li class="collection-header"><h6>Users</h6></li>
         <?php  foreach ($users as $user) { ?>
           <a href="chat.php?id=<?php echo $user['id'] ?>" onclick="messagesSeen(<?php echo $you['id'] ?>,<?php echo $_SESSION['user_id'] ?>)" class="collection-item">
             <span><?php echo $user['name']; ?></span>
             <?php
               $your_id = $you['id'];
               $user_id = $user['id'];
               $mysql = 'SELECT * FROM chat WHERE user_id = ? AND your_id = ? AND seen = false';
               $query = $pdo->prepare($mysql);
               $query->execute([$your_id,$user_id]);
               $data = $query->rowCount();
             if ($data !== 0) { ?>
               <span class="new badge" id='aaaa'><?php echo $data ?></span>
             <?php }?>
           </a>
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

       function myFunction(user,you) {
         // alert(user + '%' + you)
         let xmlHttp = new XMLHttpRequest();
         xmlHttp.onreadystatechange = function()
           {
             if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
             {
               alert(xmlHttp.response);
               // data = JSON.parse(xmlHttp.response);
             }
           }
           xmlHttp.open("post", "messagesUnseen.php");
           xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
           xmlHttp.send('user_id=' + you+'& your_id=' + user);
         }
     </script>
     <script src="./chat.js"></script>
   </body>
 </html>
<?php } else {
  header('Location: http://localhost/pdo/client/admin.php');
  exit();
} ?>
