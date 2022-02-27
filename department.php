<?php
//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
//loginCheck();

//以下ログインユーザーのみ

$pdo = db_conn();
//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM department_mst where activeindicator='Y' order by departmentno;");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false) {
  sql_error($stmt);
}else{
    $view .= '<p>';
    $view .= '<TABLE border="1" width="500" style="font-size: 10pt">';
    //タイトル表示
    $view .= '<tr bgcolor="#FFDBC9">';
    $view .= '<th>No.</th>';
    $view .= '<th>部門名</th>';
    $view .= '<th></th>';
    $view .= '</tr>';
  while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= '<tr>';
    ;
    $view .= '<th>'.$r["departmentno"].'</th>';
    $view .= '<th>'.$r["departmentname"].'</th>';
    $view .= '<th><a class="btn btn-danger" href="departmentdelete.php?departmentno='.$r["departmentno"].'">';
    $view .= '[<i ></i>削除]';
    $view .= '</a></th>';
    $view .= '</tr>';
  }
}
    $view .= '</TABLE>';
    $view .= '</p>';
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>部門管理</title>
<link rel="stylesheet" href="css/base.css">
<style></style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
      <a href="menu.php">メニューへ戻る</a>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="departmentinsert.php">
    <fieldset>
    <legend>◆部門追加◆</legend><br>
     <label>No.：<input type="text" name="departmentno" maxlength="5" size="5"></label><br>
     <label>部門名：<input type="text" name="departmentname"></label><br><br>
     <input type="submit" value="追加">
    </fieldset>
</form>
<br>
<legend>◆登録済出社先◆</legend>
<div>
    <?= $view ?>
</div>

<!-- Main[End] -->

</body>
</html>
