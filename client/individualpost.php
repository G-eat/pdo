<?php
  include_once('../config/connect.php');
  include_once('../models/post.php');
  include_once('../models/comment.php');

  $post = new Post;
  $comment = new Comment;

    // get comment from form
    if (filter_has_var(INPUT_POST,'submit')) {
      $addcomment = htmlspecialchars($_POST["comment"]);
      if(empty($addcomment)){
        echo "<h3>Fill the fields.</h3>";
      }else{
        $id = $_GET['id'];
        $comment->add_comment($id,$addcomment);
        header('Location: http://localhost/pdo/client/individualpost.php?id='.$id);
      }
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
                <li><a href="http://localhost/pdo/client/admin.php">Admin</a></li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="container">
          <div class="row">
            <div class="col s12 m12">
              <div class="card blue-grey darken-1">
                <div class="card-content white-text">
                  <span class="card-title center-align"><span class='amber-text text-darken-2'><?php echo $data['title'] ?></span></span><hr><br>
                  <p><?php echo $data['body'] ?></p>
                </div>
                <div class="card-action">
                  <span>Created_at <?php echo $data['created_at'] ?> by : <span class='amber-text'><?php echo $data['author'] ?></span></span>
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
              <a href="#!" class="collection-item">
                <?php echo $comment['comment']; ?>
              </a>
            <?php }?>
          </ul>
          <br>
          <a href='http://localhost/pdo/client/'>&larr; Back</a>
          <br><br>
        </div>

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script type="text/javascript">
          $('#textarea1').val('New Text');
          M.textareaAutoResize($('#textarea1'));
        </script>
      </body>
    </html>
    <?php
  }else{
    header('Location: post.php');
    exit();
  }
?>
