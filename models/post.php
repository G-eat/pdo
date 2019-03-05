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

  function xml_all()
  {
    global $pdo;

    $mysql = "SELECT * FROM posts";
    $query = $pdo->prepare($mysql);
    $query->execute();

    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput=true;

    $posts = $xml->createElement('posts');
    $xml->appendChild($posts);

    foreach ( $query->fetchAll() as $post1) {
      $post = $xml->createElement('post');

      $id = $xml->createElement('id',$post1['id']);
      $post->appendChild($id);

      $category = $xml->createElement('category',$post1['category']);
      $post->appendChild($category);

      $title = $xml->createElement('title',$post1['title']);
      $post->appendChild($title);

      $body = $xml->createElement('body',$post1['body']);
      $post->appendChild($body);

      $created_at = $xml->createElement('created_at',$post1['created_at']);
      $post->appendChild($created_at);

      $posts->appendChild($post);
    }



    $xml->save('api\posts.xml');
  }
}

//info for all db

// comments = id,post_id,comment(255),created_at
// posts = id,category(255),title(255),body(text),created_at
// users = id,name,admin(dafault false),password,created_at
// reposrt = id,post_id,report(255),created_at

?>
