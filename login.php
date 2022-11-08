<?php
  // DB接続クラスを使うには、予め読み込みをしておく必要があります。includeは、指定ファイルを読み込む関数
  include 'lib/connect.php';

  // エラーメッセージを入れる変数を作る。デフォはnull
  $err = null;

  if (isset($_POST['name']) && isset($_POST['password'])){
    // connectクラスのインスタンスを作成
    $db = new connect();

    // 実行したいSQL文。usersテーブルからnameが:nameのレコードを取得させる
    $select = "SELECT * FROM users WHERE name=:name";
    // 第2引数でどのパラメータにどの変数を割り当てるか決める
    $stmt = $db->query($select, array(':name' => $_POST['name']));
    // レコード1件を連想配列として取得する
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // パスワードのチェック
    if ($result && password_verify($_POST['password'], $result['password'])){
      // 結果が存在し、パスワードも正しい場合
      session_start();
      $_SESSION['id'] = $result['id'];
      header('Location: backend.php');
    } else {
      $err = "ログインできませんでした。";
    }
  }
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <style>
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
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="./css/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">

    <main class="form-signin">
    <form action="login.php" method="post">
        <h1 class="h3 mb-5 fw-normal">管理画面 ログイン</h1>

        <?php
          if (!is_null($err)){
            echo '<div class="alert alert-danger">'.$err.'</div>';
          }
        ?>

        <label class="visually-hidden">ユーザ名</label>
        <input type="text" name="name" class="mb-2 form-control" placeholder="ユーザ名を入力してください" required autofocus>
        <label class="visually-hidden">パスワード</label>
        <input type="password" name="password" class="mb-4 form-control" placeholder="パスワードを入力してください" required>
        <button class="w-100 btn btn-lg btn-primary" type="submit">ログイン</button>

      </form>
    </main>

    <footer class="mt-5 mb-3 text-muted">&copy; S.Kikuchi 2021</footer>
  </body>
</html>

