<?php
// 管理者検索結果画面
// echo("該当者特定画面に飛びました。");

//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
//loginCheck();

//0. POSTデータ取得
$view = $_POST['view'];
$where = $_POST['where'];

echo $where;

//以下ログインユーザーのみ

//テーブルのヘッダー表示
$view = '';
$view .= '<p>';
$view .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
//タイトル表示
$view .= '<tr bgcolor="#FFDBC9">';
$view .= '<th width="50">No.</th>';
$view .= '<th width="150">出勤日</th>';
$view .= '<th width="100">出勤時間</th>';
$view .= '<th width="600">退勤時間</th>';
$view .= '<th width="600">社員番号</th>';
$view .= '<th width="600">社員名</th>';
$view .= '<th width="600">所属</th>';
$view .= '<th width="600">出社先</th>';
$view .= '<th width="600">備考</th>';
$view .= '</tr>';

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>管理者確認画面</title>
<link rel="stylesheet" href="css/base.css">
<style></style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
      <a href="menu.php">メニューへ戻る</a>
      <br>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="administratorsearch.php">
    <fieldset>
    <legend>◆行動履歴の検索◆</legend><br>
     <label>日付<input type="date" name="date" maxlength="4" size="4"></label><br>
     <label>社員番号：<input type="" name="empno"></label><br>
     <input type="submit" value="検索">
    </fieldset>
</form>
<br>
<div> ◆該当人物の行動履歴を表示します◆
  <?= $view ?> 
</div>
<br>
<div>◆接触者を一覧表示します◆
    <?= $view ?>
</div>

<!-- Main[End] -->

</body>
</html>
