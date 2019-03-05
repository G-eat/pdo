<?php
  session_start();

  include_once('../config/connect.php');
  include_once('../models/post.php');
  include_once('../models/comment.php');

  $post = new Post;
  $comment = new Comment;

  //comments xml
  $comment->xml_comments();


    // get comment from form
    if (filter_has_var(INPUT_POST,'submit')) {
      $addcomment = htmlspecialchars($_POST["comment"]);
      if(empty($addcomment)){
        echo "<h3>Fill the fields.</h3>";
      }else{
        $id = $_GET['id'];
        $user_name = $_SESSION['log_in'];
        if ( $user_name == '') {
          $user_name = $_SESSION['admin'];
        }
        $comment->add_comment($id,$addcomment,$user_name);
        header('Location: http://localhost/pdo/client/individualpost.php?id='.$id);
      }
    }

    if (filter_has_var(INPUT_POST,'delete_comment')) {
      $id = $_POST["del_comment"];
      $post_id = $_GET['id'];
      $mysql = 'DELETE FROM comments WHERE id = ?';
      $query = $pdo->prepare($mysql);
      $query->execute([$id]);
      header('Location: http://localhost/pdo/client/individualpost.php?id='.$post_id);
      }

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $post->individual_post($id);
    $comments = $comment-> comments_of_post($id);

    ?>
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title>Pdo | Post</title>
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
                <li><a href="http://localhost/pdo/client/create_post.php">Create Post</a></li>
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
                <li><a href="http://localhost/pdo/client/create_post.php" class="white-text">Create Post</a></li>
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

        <!-- page not fount -->
        <?php
          if ($data == 0) {
            echo '<div class="container">
                    <div class="card-panel red lighten-2">
                      Page not found or was deleted.
                    </div>
                    <a href="http://localhost/pdo/client/">&larr; Back</a>
                  </div>';
          }else{
        ?>

        <div class="container">
          <div class="row">
            <div class="col s12 m12">
              <div class="card blue-grey darken-1">
                <div class="card-content white-text">
                  <span class="card-title center-align"><h5 class='amber-text text-darken-2'><?php echo $data['title'] ?></h5></span><hr><br>
                  <p><?php echo $data['body'] ?></p>
                </div>
                <div class="card-action">
                  <span>Created_at <span class='amber-text'><?php echo $data['created_at'] ?></span> in category : <span class='amber-text'><?php echo $data['category'] ?></span></span>
                  <span class="orange-text darken-4" style="float:right;"><?php echo $data['post_user'] ?></span>
                </div>
              </div>
            </div>
          </div>

          <h6>Add Comment</h6>
          <form method="post">
            <div class="row">
              <div class="input-field">
                <textarea id="textarea1" name='comment' class="materialize-textarea"></textarea>
                <label for="textarea1">Comment</label>
              </div>
              <button name='submit' class="btn waves-effect waves-light" type="submit" name="action">Submit</button>
            </div>
          </form>

          <ul class="collection with-header">
            <li class="collection-header"><h6>All Coments</h6></li>
            <?php  foreach ($comments as $comment) { ?>
              <blockquote  class="collection-item" style="padding:0.1rem; border-top:1px #ee6e73 solid;" >
                <a href="#!" class="collection-item" style="margin:0;padding:0.3rem;">
                  <span style="color:rgba(0, 0, 0, 0.5);margin:0;padding:0"><?php echo $comment['user_name']; ?> - <?php echo $comment['created_at']; ?></span>
                  <hr style="height: 0.6rem;border: 0;box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);">
                  <?php echo $comment['comment']; ?>
                  <?php if ((isset($_SESSION['log_in']) && $comment['user_name'] == $_SESSION['log_in']) || isset($_SESSION['admin']) ): ?>
                    <span style="float:right">
                      <form method="post">
                        <input type="hidden" name='del_comment' value="<?php echo $comment['id'] ?>">
                        <input style="background:transparent;"  type="submit" name="delete_comment" value="&#10006;" />
                      </form>
                    </span>
                  <?php endif; ?>
                </a>
              </blockquote>
              <br>
            <?php }?>
          </ul>
          <br>
          <a href='http://localhost/pdo/client/'>&larr; Back</a>
          <?php if (isset($_SESSION['admin'])) { ?>
            <form style="float:right" method="post" action="admin_dashbord.php">
              <input type="hidden" name='id' value="<?php echo $_GET['id'] ?>">
              <input class=" btn red lighten-2" type="submit" name="submit" value="DELETE &#10006;" />
            </form>
          <?php } else {?>
            <!-- Modal Trigger -->
            <a style="float:right" class="btn blue lighten-4 blue-text modal-trigger" href="#modal1"><i class="material-icons">report</i>Report</a>
          <?php } ?>
          <br><br><br><br>

           <!-- Modal Structure -->
          <div id="modal1" class="modal">
            <div class="modal-content">
              <h4>Report about post.</h4><br>
              <form method="post" action="modal.php">
                <div class="row">
                  <div class="input-field">
                    <i class="material-icons prefix">mode_edit</i>
                    <textarea id="icon_prefix2" name='report' class="materialize-textarea" required></textarea>
                    <input type="hidden" name="post_id" value=<?php echo $_GET['id'] ?>>
                    <label for="report">Report</label>
                  </div>
                  <div class="modal-footer">
                    <button name='submit' class="btn waves-effect waves-light" type="submit" name="action">Report</button>
                    <button type="button" name="cancel" class="btn waves-effect waves-light grey lighten-1 modal-close">Cancel</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            let elems = document.querySelectorAll('.sidenav');
            let instances = M.Sidenav.init(elems, {draggable:true});
          });

          //modal
          document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems, {onOpenEnd:true});
          });
        </script>
      </body>
    </html>
    <?php
  }

  }else{
    header('Location: post.php');
    exit();
  }
?>
