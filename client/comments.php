<?php
  include_once('../config/connect.php');
  include_once('../models/comment.php');

  $comment = new Comment;

  $comments = $comment->comments();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Comments</title>
   </head>
   <body>
     <?php foreach ($comments as $comment) { ?>
       <li>
         <?php
          echo $comment['comment'];
         ?>
       </li>
     <?php } ?>
   </body>
 </html>
