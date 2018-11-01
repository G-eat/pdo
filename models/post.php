<?php
class Post
{

  public function get_all()
  {
    global $pdo;

    $mysql = "SELECT * FROM posts";
    $query = $pdo->prepare($mysql);
    $query->execute();

    return $query->fetchAll();
  }

  public function individual_post($id)
  {
    global $pdo;

    $mysql = 'SELECT * FROM posts WHERE id=?';
    $query = $pdo->prepare($mysql);
    $query->bindValue(1,$id);
    $query->execute();

    return $query->fetch();
  }
}

?>
