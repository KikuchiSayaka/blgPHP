<?php
  session_start();
  if (isset($_SESSION['id'])){
    // ログインしていたら、$_SESSION['id']を削除
    unset($_SESSION['id']);
  }
  // ログインページに遷移
  header('Location: login.php');
?>