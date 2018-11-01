<?php
  class Comment
  {
    public function comments()
    {
      global $pdo;

      $mysql = 'SELECT * FROM comments';
      $query = $pdo->prepare($mysql);
      $query->execute();

      return $query->fetchAll();
    }

    public function comments_of_post($id)
    {
      global $pdo;

      $mysql = 'SELECT * FROM comments WHERE post_id = ?';
      $query = $pdo->prepare($mysql);
      $query->bindValue(1,$id);
      $query->execute();

      return $query->fetchAll();
    }

    public function add_comment($id,$addcomment)
    {
      global $pdo;

      $mysql = 'INSERT INTO comments (id, post_id, comment, created_at) VALUES (NULL,?,?, CURRENT_TIMESTAMP)';
      $query = $pdo->prepare($mysql);
      $query->execute([$id,$addcomment]);

      return $query->fetchAll();
    }
  }

 ?>
