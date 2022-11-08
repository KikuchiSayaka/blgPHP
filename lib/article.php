<?php

class Article{
  // パラメータは外部から変更されたくないので、private
  // こうすることで、$article->id=XXと、直接値を設定できなくなる。設定するときはメソッドを通して$article->setID(XX)としないと設定できない
  // new Article()をした段階では、初期値がnull
  private $id = null;
  private $title = null;
  private $body = null;
  private $category_id = null;
  private $filename = null;
  private $file = null;
  private $created_at = null;
  private $updated_at = null;

  // saveメソッド
  // QueryArticleクラスのインスタンスを作成して、setArticleメソッドで、Articleクラスのインスタンスである自分自身をセットし、保存動作を実行
  // テーブルとレコード(投稿内容)を繋ぐ役割をして、投稿があったら、テーブルへ投稿内容を保存するように渡す
  public function save(){
    $queryArticle = new QueryArticle();
    $queryArticle->setArticle($this);
    $queryArticle->save();
  }

  public function getId(){
    return $this->id;
  }

  public function getTitle(){
    return $this->title;
  }

  public function getBody(){
    return $this->body;
  }

  public function getCategoryId(){
    return $this->category_id;
  }

  public function getFilename(){
    return $this->filename;
  }

  public function getFile(){
    return $this->file;
  }

  public function getCreatedAt(){
    return $this->created_at;
  }

  public function getUpdatedAt(){
    return $this->updated_at;
  }

  public function setId($id){
    $this->id = $id;
  }

  public function setTitle($title){
    $this->title = $title;
  }

  public function setBody($body){
    $this->body = $body;
  }

  public function setCategoryId($category_id){
    $this->category_id = $category_id;
  }

  public function setFilename($filename){
    $this->filename = $filename;
  }

  public function setFile($file){
    $this->file = $file;
  }

  public function setCreatedAt($created_at){
    $this->created_at = $created_at;
  }

  public function setUpdatedAt($updated_at){
    $this->updated_at = $updated_at;
  }
}
