<?php
//セッションをスタート
session_start();

$user_id = $_SESSION['user_id'];

//関数を呼び出して
require_once('funcs.php');

//ログインチェック
loginCheck();

//以下ログインユーザーのみ

$pdo = db_conn();

//３．データ表示
$view="";
if($==false) {
  sql_error($stmt);
}else{
  while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= '<div class="menu">';
    $view .= '<a href="workplace.php">出勤先管理</a>';
    $view .= '<a href="department.php">部門管理</a>'
    $view .= '<a href="employee.php">社員マスタ管理</a>';
    $view .= '<a href="user.php">ユーザー管理</a>';
    $view .= '<a href="administratorconfirm.php">管理者確認ページ(一覧表示・データ修正)</a>';
  }
}
?>
<!-- <div class="menu">
         <a href="workplace.php">出勤先管理</a>
         <br><br>
         <a href="department.php">部門管理</a>
         <br><br>
         <a href="employee.php">社員マスタ管理</a>
         <br><br>
         <a href="user.php">ユーザー管理</a>
         <br><br>
         <a href="administratorconfirm.php">管理者確認ページ(一覧表示・データ修正)</a> -->
     </div>


<!DOCTYPE html>
<html lang="ja">
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/base.css">
    <title>メニュー一覧</title>
</head>
<!-- ここからヘッダー -->
<body>
<header>
    <h1 class="header-title">メインメニュー</h1>
    <nav class="nav">
      <ul class="menu-group">
        <li class="menu-item"><a href="#">項目1</a></li>
        <li class="menu-item"><a href="#">項目2</a></li>
        <li class="menu-item"><a href="#">項目3</a></li>
        <li class="menu-item"><a href="#">項目4</a></li>
        <li class="menu-item"><a href="#">項目5</a></li>
      </ul>
    </nav>
  </header>
</body>

<!-- ここからテキストリンク -->
<div class="menu_box">
     <div class="menu">
         <a href="workreg.php?empno=">出社登録</a>
         <br><br>
         <a href="workdetails.php?empno=">登録内容確認</a>
     </div>
</div>



</html>