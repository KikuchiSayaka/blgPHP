<?php
// セッションを使うときは必ずsession_start()をする
  session_start();
  // ログインの判別
  if (!isset($_SESSION['id'])){
    header('Location: login.php');
  }
?>