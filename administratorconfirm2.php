<?php
// 管理者確認画面

//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

//ログインチェック
//loginCheck();

//以下ログインユーザーのみ

//テーブルのヘッダー表示
// $view = '';
// $view .= '<p>';
// $view .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
// //タイトル表示
// $view .= '<tr bgcolor="#FFDBC9">';
// $view .= '<th width="50">No.</th>';
// $view .= '<th width="200">出勤日</th>';
// $view .= '<th width="200">出勤時間</th>';
// $view .= '<th width="200">退勤時間</th>';
// $view .= '<th width="200">社員番号</th>';
// $view .= '<th width="250">社員名</th>';
// $view .= '<th width="300">所属</th>';
// $view .= '<th width="250">出社先</th>';
// $view .= '<th width="400">備考</th>';
// $view .= '</tr>';

//POSTデータ取得
$date = $_POST['date'];
$empno = $_POST['empno'];

$view = '';
$view2 = '';
$workdate = array();
$starttime = array();
$endtime = array();
$place = array();

// 検索ボタン押下時処理
if (isset($_POST["search"])) {
  
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

    // WHERE句を作成
    $where = ' WHERE NOT (transaction.empno = "'.$empno.'") and (';
    $j = 0;
    for($i = 0 ; $i < count($starttime); $i++){
     $where .= '(transaction.workdate = "'.$workdate[$i]. '" and ';
     $where .= '(transaction.starttime <= "'.$endtime[$i].'" or ';
     $where .= 'transaction.endtime <= "'.$starttime[$i].'" ) ';
     $where .= 'and workplace_mst.workplacename = "'.$place[$i].'" ) ';
     if ($j < count($starttime)-1) {
        $where .= ' or ';
     }
     $j = $j +1;
    }
    $where .= ')';

    // var_dump($workdate);
    // var_dump($starttime);
    // var_dump($endtime);
    // var_dump($place);
    // echo $where;

    // 接触者検索のSQL作成
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
    transaction.workplaceno = workplace_mst.workplaceno ".$where);
    $status = $stmt->execute();

    if($status==false) {
        sql_error($stmt);
      }else{
        $view2 .= '<legend>◆接触者を表示します◆</legend>';
        $view2 .= '<p>';
        $view2 .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
        //タイトル表示
        $view2 .= '<tr bgcolor="#FFDBC9">';
        $view2 .= '<th width="50">No.</th>';
        $view2 .= '<th width="200">出勤日</th>';
        $view2 .= '<th width="200">出勤時間</th>';
        $view2 .= '<th width="200">退勤時間</th>';
        $view2 .= '<th width="200">社員番号</th>';
        $view2 .= '<th width="250">社員名</th>';
        $view2 .= '<th width="300">所属</th>';
        $view2 .= '<th width="250">出社先</th>';
        $view2 .= '<th width="400">備考</th>';
        $view2 .= '</tr>';
        while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
          $view2 .= '<tr>';
          $view2 .= '<th>'.$r["recordID"].'</th>';
          $view2 .= '<th>'.$r["workdate"].'</th>';
          $view2 .= '<th>'.$r["starttime"].'</th>';
          $view2 .= '<th>'.$r["endtime"].'</th>';
          $view2 .= '<th>'.$r["empno"].'</th>';
          $view2 .= '<th>'.$r["empname"].'</th>';
          $view2 .= '<th>'.$r["departmentname"].'</th>';
          $view2 .= '<th>'.$r["workplacename"].'</th>';
          $view2 .= '<th>'.$r["remarks"].'</th>';
          $view2 .= '</tr>';
        }
      }
  $view2 .= '</TABLE>';
  $view2 .= '</p>';
  $view2 .= '<br>';
  $view2 .= '<form method="POST" action="administratorconfirm2.php">
  <input type="submit" name="fileexport" value="接触者をファイルに出力する">
</form>'; 
   
}

// ファイル出力ボタン処理
if (isset($_POST["fileexport"])) {
  $date = $_POST['date'];
  $empno = $_POST['empno'];
  echo $date;
  var_dump($date);

//   $empno = $_POST['empno'];
//   //1.DB接続
// $pdo = db_conn();
// //２．データ検索SQL作成
// $stmt = $pdo->prepare("SELECT transaction.recordID,transaction.workdate,transaction.starttime,transaction.endtime,transaction.empno,employee_mst.empname,department_mst.departmentname,workplace_mst.workplacename,transaction.remarks
// FROM
// transaction
// JOIN
// employee_mst
// ON
// transaction.empno = employee_mst.empno
// JOIN
// department_mst
// ON
// employee_mst.departmentno = department_mst.departmentno 
// JOIN
// workplace_mst
// ON
// transaction.workplaceno = workplace_mst.workplaceno
// WHERE transaction.workdate = :date and transaction.empno = :empno");

// //３．バインド変数設定
// $stmt->bindValue(":date", date("Y-m-d", strtotime($date)), PDO::PARAM_STR);
// $stmt->bindValue(':empno', $empno, PDO::PARAM_STR);
// $status = $stmt->execute();
// //４．検索結果
// if($status==false) {
//     sql_error($stmt);
//   }else{
//     $view .= '<p>';
//     $view .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
//     //タイトル表示
//     $view .= '<tr bgcolor="#FFDBC9">';
//     $view .= '<th width="50">No.</th>';
//     $view .= '<th width="200">出勤日</th>';
//     $view .= '<th width="200">出勤時間</th>';
//     $view .= '<th width="200">退勤時間</th>';
//     $view .= '<th width="200">社員番号</th>';
//     $view .= '<th width="250">社員名</th>';
//     $view .= '<th width="300">所属</th>';
//     $view .= '<th width="250">出社先</th>';
//     $view .= '<th width="400">備考</th>';
//     $view .= '</tr>';
//     while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
//       $view .= '<tr>';
//       $view .= '<th>'.$r["recordID"].'</th>';
//       $view .= '<th>'.$r["workdate"].'</th>';
//       $view .= '<th>'.$r["starttime"].'</th>';
//       $view .= '<th>'.$r["endtime"].'</th>';
//       $view .= '<th>'.$r["empno"].'</th>';
//       $view .= '<th>'.$r["empname"].'</th>';
//       $view .= '<th>'.$r["departmentname"].'</th>';
//       $view .= '<th>'.$r["workplacename"].'</th>';
//       $view .= '<th>'.$r["remarks"].'</th>';
//       $view .= '</tr>';
//       $workdate[] = $r["workdate"];
//       $starttime[] = $r["starttime"];
//       $endtime[] = $r["endtime"];
//       $place[] = $r["workplacename"];
//     }
//   }
//     $view .= '</TABLE>';
//     $view .= '</p>';

//     // WHERE句を作成
//     $where = ' WHERE NOT (transaction.empno = "'.$empno.'") and (';
//     $j = 0;
//     for($i = 0 ; $i < count($starttime); $i++){
//      $where .= '(transaction.workdate = "'.$workdate[$i]. '" and ';
//      $where .= '(transaction.starttime <= "'.$endtime[$i].'" or ';
//      $where .= 'transaction.endtime <= "'.$starttime[$i].'" ) ';
//      $where .= 'and workplace_mst.workplacename = "'.$place[$i].'" ) ';
//      if ($j < count($starttime)-1) {
//         $where .= ' or ';
//      }
//      $j = $j +1;
//     }
//     $where .= ')';

//     // var_dump($workdate);
//     // var_dump($starttime);
//     // var_dump($endtime);
//     // var_dump($place);
//     // echo $where;

//     // 接触者検索のSQL作成
//     $stmt = $pdo->prepare("SELECT transaction.recordID,transaction.workdate,transaction.starttime,transaction.endtime,transaction.empno,employee_mst.empname,department_mst.departmentname,workplace_mst.workplacename,transaction.remarks
//     FROM
//     transaction
//     JOIN
//     employee_mst
//     ON
//     transaction.empno = employee_mst.empno
//     JOIN
//     department_mst
//     ON
//     employee_mst.departmentno = department_mst.departmentno 
//     JOIN
//     workplace_mst
//     ON
//     transaction.workplaceno = workplace_mst.workplaceno ".$where);
//     $status = $stmt->execute();

//     if($status==false) {
//         sql_error($stmt);
//       }else{
//         $view2 .= '<legend>◆接触者を表示します◆</legend>';
//         $view2 .= '<p>';
//         $view2 .= '<TABLE border="1" width="1000" style="font-size: 10pt">';
//         //タイトル表示
//         $view2 .= '<tr bgcolor="#FFDBC9">';
//         $view2 .= '<th width="50">No.</th>';
//         $view2 .= '<th width="200">出勤日</th>';
//         $view2 .= '<th width="200">出勤時間</th>';
//         $view2 .= '<th width="200">退勤時間</th>';
//         $view2 .= '<th width="200">社員番号</th>';
//         $view2 .= '<th width="250">社員名</th>';
//         $view2 .= '<th width="300">所属</th>';
//         $view2 .= '<th width="250">出社先</th>';
//         $view2 .= '<th width="400">備考</th>';
//         $view2 .= '</tr>';
//         while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
//           $view2 .= '<tr>';
//           $view2 .= '<th>'.$r["recordID"].'</th>';
//           $view2 .= '<th>'.$r["workdate"].'</th>';
//           $view2 .= '<th>'.$r["starttime"].'</th>';
//           $view2 .= '<th>'.$r["endtime"].'</th>';
//           $view2 .= '<th>'.$r["empno"].'</th>';
//           $view2 .= '<th>'.$r["empname"].'</th>';
//           $view2 .= '<th>'.$r["departmentname"].'</th>';
//           $view2 .= '<th>'.$r["workplacename"].'</th>';
//           $view2 .= '<th>'.$r["remarks"].'</th>';
//           $view2 .= '</tr>';
//         }
//       }
//   $view2 .= '</TABLE>';
//   $view2 .= '</p>';
//   $view2 .= '<br>';
//   $view2 .= '<form method="POST" action="administratorconfirm2.php">
//   <input type="submit" name="fileexport" value="接触者をファイルに出力する">
//   </form>'; 
   
}

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
<form method="POST" action="administratorconfirm2.php">
    <fieldset>
    <legend>◆行動履歴の検索◆</legend><br>
     <label>日付<input type="date" name="date" maxlength="4" size="4" required></label><br>
     <label>社員番号：<input type="text" name="empno" required></label><br>
     <!-- <label>郵便番号：<input type="text" name="postcode" maxlength="7" size="7"></label><br>
     <label>住所：<input type="text" name="address" maxlength="40" size="40"></label><br>
     <br> -->
     <input type="submit" name="search" value="検索">
     <!-- <input type="submit" name="fileexport" value="接触者をファイルに出力する"> -->
    </fieldset>
</form>
<br>
<legend>◆該当人物の行動履歴を表示します◆</legend>
<div>
    <?= $view ?>
</div>

<!-- <legend>◆接触者を表示します◆</legend> -->
<div>
    <?= $view2 ?>
    
</div>

<!-- エクセル出力のボタン -->
<!-- <form method="POST" action="administratorconfirm2.php">
  <input type="submit" name="fileexport" value="接触者をファイルに出力する">
</form> -->

<!-- Main[End] -->

</body>
</html>
