<?php 
// ここではDBに接続し、SQLを実行するクラスを定義する

// クラス定数
class connect {
  const DB_NAME = 'blog';
  const HOST = 'localhost';
  const USER = 'user';
  const PASS = 'pass';

  // メンバ変数
  // connectクラスだけでなく、 queryArticle.phpで使うのでprotected
  // $dbhはData Base Handle の略で、DBに接続したのち、そのDBを操作するための情報を代入しておきます。操作できる状態、つまり車でいうハンドルを入れておきます。
  protected $dbh;

  public function __construct(){
    // DSNを設定。Data Source Name の略で、どのホストのどのDBに接続するのかを示している。
    $dsn = "mysql:host=".self::HOST.";dbname=".self::DB_NAME.";charset=utf8mb4";

    try {
      // 接続PDOインスタンスをクラス変数に格納する
      $this->dbh = new PDO($dsn, self::USER, self::PASS);

    } catch(Exception $e){
      // 接続できなかった場合(Exception)が発生したら表示して終了
      exit($e->getMessage());
    }

    // setAttributeメソッドで、第1引数でDBのエラーを表示するモード、第2引数でエラー時にWarningを発生させるよう指定
    $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

  // queryメソッドで第1引数がSQL文で必須です。第2引数は割り当てるパラメータ
  public function query($sql, $param = null){
    // プリペアドステートメントを作成し、SQL文を実行する準備をする
    $stmt = $this->dbh->prepare($sql);
    // パラメータを割り当てて実行し、結果セットを返す
    $stmt->execute($param);
    return $stmt;
  }

}
?>