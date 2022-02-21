<?php
// 管理者検索結果画面
echo("検索結果画面に飛びました");

//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
//loginCheck();

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
    <legend>◆該当人物の行動履歴の検索◆</legend><br>
     <label>日付<input type="date" name="date" maxlength="4" size="4"></label><br>
     <label>社員番号：<input type="" name="empno"></label><br>
     <!-- <label>郵便番号：<input type="text" name="postcode" maxlength="7" size="7"></label><br>
     <label>住所：<input type="text" name="address" maxlength="40" size="40"></label><br>
     <br> -->
     <!-- <input type="submit" value="検索"> -->
     <input type="submit" value="検索">
    </fieldset>
</form>
<br>
<legend>◆該当人物の行動履歴を表示します◆</legend>
<div>
    <?= $view ?>
    
</div>

<form method="POST" action="administratoridentification.php"> 
    <!-- 検索結果を隠して特定ページへ -->
    <input type="hidden" name="all" value=<?php  ?> >
    <input type="submit" value="接触者を特定">
</form>

<!-- Main[End] -->

</body>
</html>
