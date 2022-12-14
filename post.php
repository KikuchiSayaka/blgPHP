<?php
  include 'lib/secure.php';
  include 'lib/connect.php';
  include 'lib/queryArticle.php';
  include 'lib/article.php';

  $title = "";        // タイトル
  $body = "";         // 本文
  $title_alert = "";  // タイトルのエラー文言
  $body_alert = "";   // 本文のエラー文言

  if (!empty($_POST['title']) && !empty($_POST['body'])){
    // titleとbodyがPOSTメソッドで送信されたとき
    $title = $_POST['title'];
    $body = $_POST['body'];

    // レコードをオブジェクトとして扱う
    $article = new Article();
    $article->setTitle($title);
    $article->setBody($body);

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])){
      $article->setFile($_FILES['image']);
    }

    $article->save();

    header('Location: backend.php');
  } else if(!empty($_POST)){
    // POSTメソッドで送信されたが、titleかbodyが足りないとき
    // 存在するほうは変数へ、ない場合空文字にしてフォームのvalueに設定する
    if (!empty($_POST['title'])){
      $title = $_POST['title'];
    } else {
      $title_alert = "タイトルを入力してください。";
    }

    if (!empty($_POST['body'])){
      $body = $_POST['body'];
    } else {
      $body_alert = "本文を入力してください。";
    }
  }
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Backend</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <style>
      body {
        padding-top: 5rem;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }

        .navbar-collapse {
          width: 20%;
        }
      }

      .bg-red {
        background-color: #EB8686 !important;
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="./css/blog.css" rel="stylesheet">
  </head>
  <body>

  <?php include('lib/nav.php'); ?>

  <main class="container">
    <div class="row">
      <div class="col-md-12">

      <h1>記事の投稿</h1>

      <form action="post.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">タイトル</label>

          <?php
          // 三項演算子。？までの内容がtrueの時、？以降の処理をする。？までの内容がfalseの時、:以降を返す。
          // この場合、変数title_alertが空でない時、エラー文出力。そうでなければ空文字列を返す
          echo !empty($title_alert)? '<div class="alert alert-danger">'.$title_alert.'</div>': '' ?>

          <input type="text" name="title" value="<?php echo $title; ?>" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">本文</label>
          <?php
          // ここも変数body_alertが空でなければエラー文。空なら空文字列を返す。
          echo !empty($body_alert)? '<div class="alert alert-danger">'.$body_alert.'</div>': '' ?>
          <textarea name="body" class="form-control" rows="10"><?php echo $body; ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">サムネイル画像を指定</label>
          <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">投稿する</button>
        </div>
      </form>

      </div>

    </div><!-- /.row -->

  </main><!-- /.container -->

  </body>
</html>

