<?php
// connect.phpにある、DBに接続しているconnectクラスを親クラスとして継承。これにより、QueryArticleクラスがnewされてインスタンス化されると常にDBに接続されることになる

class QueryArticle extends connect{
  private $article;


  public function __construct(){
    parent::__construct();
  }

  // プライベートなメンバ変数$articleに値をセットするメソッド(関数)。
  // このsetArticleメソッド(関数)はArticleクラス内のメンバ変数しか受け取らないように$articleの前に型としてArticle指定をしている。
  public function setArticle(Article $article){
    $this->article = $article;
  }

  // さっきセットしたメンバ変数$articleに記事データを保存する処理
  public function save(){

    // bindParam用(新規作成時・上書き時のどちらでも使う変数をここで共通して定義)
    $title = $this->article->getTitle();
    $body = $this->article->getBody();
    $filename = null;

    if ($this->article->getId()){
      // $this->article->getId()でIDが取得できた場合は、上書き処理
      $id = $this->article->getId();

      // UPDATE 対象のテーブル SET カラム=変更内容, カラム=変更内容を,区切りで繰り返す (updated_at=NOW()で今の時間になる)... WHERE 変更するレコードの条件
      $stmt = $this->dbh->prepare("UPDATE articles
                SET title=:title, body=:body, updated_at=NOW() WHERE id=:id");
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':body', $body, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();

    } else {
      // IDがなければ新規作成
      // 画像ファイルを保存するのは、画像ファイルがアップロードされていたときのみ、$fileにアップロードしたファイルについての配列を取得
      if ($file = $this->article->getFile()){
        // サーバー上のテンポラリディレクトリに保存されている画像の名前$file['tmp_name']を取得
        $old_name = $file['tmp_name'];
        // 新しい画像名を入れるために変数new_nameを現在の年月日時分秒を追加して、さらに同じ時刻に複数人からアップロードされた場合に対応するためにmt_rand()でランダム数値を追加
        $new_name = date('YmdHis').mt_rand();

        // アップロード可否を決める変数。デフォルトはアップロード不可
        $is_upload = false;

        // 画像の種類を取得する
        $type = exif_imagetype($old_name);
        // ファイルの種類が画像だったとき、種類によって拡張子を変更
        switch ($type){
          case IMAGETYPE_JPEG:
            $new_name .= '.jpg';
            $is_upload = true;
            break;
          case IMAGETYPE_GIF:
            $new_name .= '.gif';
            $is_upload = true;
            break;
          case IMAGETYPE_PNG:
            $new_name .= '.png';
            $is_upload = true;
            break;
        }

        if ($is_upload && move_uploaded_file($old_name, __DIR__.'/../album/'.$new_name)){
          $this->article->setFilename($new_name);
          $filename = $this->article->getFilename();
        }
      }


      // 親クラスのパラメータを使用している
      $stmt = $this->dbh->prepare("INSERT INTO articles (title, body, created_at, updated_at)
                VALUES (:title, :body, NOW(), NOW())");
      // さっき取得したプリペアステートメントにbindParamで値を割り当てる。第一引数にプレースフォルダ、第二引数に変数、第三引数に割り当てるデータ型を組み込み定数で指定する
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':body', $body, PDO::PARAM_STR);
      // bindParamしたものを実行するexcute関数
      $stmt->execute();
    }
  }

  public function find($id){
    $stmt = $this->dbh->prepare("SELECT * FROM articles WHERE id=:id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $article = null;
    if ($result){
      $article = new Article();
      $article->setId($result['id']);
      $article->setTitle($result['title']);
      $article->setBody($result['body']);
      $article->setCreatedAt($result['created_at']);
      $article->setUpdatedAt($result['updated_at']);
    }
    return $article;
  }


  // 投稿一覧を取得

  public function findAll(){
    // articlesテーブルから全カラムを#stmtに入れる(WHEREでの条件が無いため全レコードを取得)
    $stmt = $this->dbh->prepare("SELECT * FROM articles");
    // 上記の$stmtに入れたSQL文の実行
    $stmt->execute();
    // articlesテーブルのカラムをfetchAllで配列にする
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // 空の配列を用意
    $articles = array();

    // さっきの空の配列に、results配列に入った投稿記事を1記事づつ入れて行って、最後にarticlesという二次元配列に格納
    foreach ($results as $result){
      // article.phpで定義されているクラスArticleをインスタンス化
      $article = new Article();
      // インスタンスにidやタイトル、記事内容をどんどん情報を放り込んでいく
      $article->setId($result['id']);
      $article->setTitle($result['title']);
      $article->setBody($result['body']);
      $article->setCreatedAt($result['created_at']);
      $article->setUpdatedAt($result['updated_at']);
      // 中身が入ったArticleインスタンスを、foreach文でどんどんarticles配列に入れる
      $articles[] = $article;
    }
    // 結果、たくさんAericleインスタンスが入った配列articlesを戻り値にする
    return $articles;
  }
}
