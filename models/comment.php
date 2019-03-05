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

    public function add_comment($id,$addcomment,$user_name)
    {
      global $pdo;

      $mysql = 'INSERT INTO comments (id,user_name, post_id, comment, created_at) VALUES (NULL,?,?,?, CURRENT_TIMESTAMP)';
      $query = $pdo->prepare($mysql);
      $query->execute([$user_name,$id,$addcomment]);

      return $query->fetchAll();
    }

    function xml_comments()
    {
      global $pdo;

      $mysql = 'SELECT * FROM comments';
      $query = $pdo->prepare($mysql);
      $query->execute();

      $xml = new DOMDocument('1.0','utf-8');
      $comments = $xml->createElement('comments');
      $xml->appendChild($comments);

      foreach ($query->fetchAll() as $comment1) {
        $comment = $xml->createElement('comment');
        $comments->appendChild($comment);

        $id = $xml->createElement('id',$comment1['id']);
        $comments->appendChild($id);

        $post_id = $xml->createElement('post_id',$comment1['post_id']);
        $comments->appendChild($post_id);

        $comment = $xml->createElement('comment',$comment1['comment']);
        $comments->appendChild($comment);

        $created_at = $xml->createElement('created_at',$comment1['created_at']);
        $comments->appendChild($created_at);
      }

      $xml->save('api/comments.xml');
    }
  }

 ?>
