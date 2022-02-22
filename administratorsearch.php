<?php
// 管理者検索結果画面

//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
//loginCheck();

//以下ログインユーザーのみ

//0. POSTデータ取得
$date = $_POST['date'];
$empno = $_POST['empno'];

//デバッグ
// echo $date;
// echo $empno;

$view = '';
$workdate = array();
$starttime = array();
$endtime = array();
$place = array();
$where = '';

//以下検索機能
//1.DB接続
$pdo = db_conn();
//２．データ検索SQL作成
$stmt = $pdo->prepare("SELECT transaction.recordID,transaction.workdate,transaction.starttime,transaction.endtime,transaction.empno,employee_mst.empname,department_mst.departmentname,workplace_mst.workplacename,transaction.remarks
FROM
transaction
JOIN
employee_mst
ON
transaction.empno = employee_mst.empno
JOIN
department_mst
ON
employee_mst.departmentno = department_mst.departmentno 
JOIN
workplace_mst
ON
transaction.workplaceno = workplace_mst.workplaceno
WHERE transaction.workdate = :date and transaction.empno = :empno");

//３．バインド変数設定
$stmt->bindValue(":date", date("Y-m-d", strtotime($date)), PDO::PARAM_STR);
$stmt->bindValue(':empno', $empno, PDO::PARAM_STR);
$status = $stmt->execute();
//４．検索結果
if($status==false) {
    sql_error($stmt);
  }else{
    $view .= '<p>';
    $view .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
    //タイトル表示
    $view .= '<tr bgcolor="#FFDBC9">';
    $view .= '<th width="50">No.</th>';
    $view .= '<th width="200">出勤日</th>';
    $view .= '<th width="200">出勤時間</th>';
    $view .= '<th width="200">退勤時間</th>';
    $view .= '<th width="200">社員番号</th>';
    $view .= '<th width="250">社員名</th>';
    $view .= '<th width="300">所属</th>';
    $view .= '<th width="250">出社先</th>';
    $view .= '<th width="400">備考</th>';
    $view .= '</tr>';
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      $view .= '<tr>';
      $view .= '<th>'.$r["recordID"].'</th>';
      $view .= '<th>'.$r["workdate"].'</th>';
      $view .= '<th>'.$r["starttime"].'</th>';
      $view .= '<th>'.$r["endtime"].'</th>';
      $view .= '<th>'.$r["empno"].'</th>';
      $view .= '<th>'.$r["empname"].'</th>';
      $view .= '<th>'.$r["departmentname"].'</th>';
      $view .= '<th>'.$r["workplacename"].'</th>';
      $view .= '<th>'.$r["remarks"].'</th>';
      $view .= '</tr>';
      $workdate[] = $r["workdate"];
      $starttime[] = $r["starttime"];
      $endtime[] = $r["endtime"];
      $place[] = $r["workplacename"];
    }
  }
    $view .= '</TABLE>';
    $view .= '</p>';
    $j = 0;
    for($i = 0 ; $i < count($starttime); $i++){
     $where .= '(transaction.workdate = "'.$workdate[$i]. '" and ';
     $where .= '(transaction.starttime <= "'.$endtime[$i].'" or ';
     $where .= 'transaction.endtime >= "'.$starttime[$i].'" ) ';
     $where .= 'and workplace_mst.workplacename = "'.$place[$i].'" ) ';
     if ($j < count($starttime)-1) {
        $where .= ' or ';
     }
     $j = $j +1;
    }

    // var_dump($workdate);
    // var_dump($starttime);
    // var_dump($endtime);
    // var_dump($place);
    echo $where;
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
    <input type="hidden" name="view" value= "<?php echo $view; ?>" >
    <input type="hidden" name="where" value= "<?php echo $where; ?>" >
    <input type="submit" value="接触者を特定">
</form>

<!-- Main[End] -->

</body>
</html>
