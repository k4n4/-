<?php

//SESSIONスタート
//session_start();

//関数を呼び出す
require_once('funcs.php');

echo("ファイルを出力しました。");
$data = array();  
$count = $_POST["count"];

for($i = 0 ; $i < $count ; $i++){
  
  for($j = 0; $j < 9 ; $j++){ 
    $data[$i][$j] = $_POST["data"][$i][$j];
  }  
}

//var_dump($data);

 try {

    // CSV形式で情報をファイルに出力のための準備
    // $csvFileName = "file.csv";
    // $fileName = time() . rand() . '.csv';
    $res = fopen('sesshokusha.csv', 'w');
    if ($res === FALSE) {
        throw new Exception('ファイルの書き込みに失敗しました。');
    }

    // 項目名を配列に格納
    $header = ["通し番号","出勤日", "出勤時間", "退勤時間", "社員番号", "社員名", "所属","出社先","備考"];
    // 項目名を出力
    mb_convert_variables('SJIS', 'UTF-8', $header);
    fputcsv($res, $header);

    // 出力テスト用配列
    // $data = array(
    //     array(
    //     "担当者" => "高橋",
    //     "重要度" => "!",
    //     "タスク名" => "aaa",
    //     "タイトル内容" => "aaaaa",
    //     "進捗状況" => "未着手",
    //     "期限" => "2021/11/30"
    //     ),
    //     array(
    //       "担当者" => "高橋",
    //       "重要度" => "!",
    //       "タスク名" => "aaa",
    //       "タイトル内容" => "aaaaa",
    //       "進捗状況" => "未着手",
    //       "期限" => "2021/11/30"
    //       )         
    // );

    //ループしながら出力
    foreach($data as $dataInfo) {
      // 文字コード変換。エクセルで開けるようにする
      mb_convert_variables('SJIS', 'UTF-8', $dataInfo);

      // ファイルに書き出しをする
      fputcsv($res, $dataInfo);
    }

    // ファイルを閉じる
    fclose($res);

    // ダウンロード開始

    // ファイルタイプ（csv）
    header('Content-Type: application/octet-stream');

    // ファイル名
    // header('Content-Disposition: attachment; filename=' . $fileName); 
    header('Content-Disposition: attachment; filename=' . 'sesshokusha.csv');
    // ファイルのサイズ　ダウンロードの進捗状況が表示
    // header('Content-Length: ' . filesize($csvFileName)); 
    header('Content-Length: ' . filesize('sesshokusha.csv')); 
    header('Content-Transfer-Encoding: binary');
    // ファイルを出力する
    // readfile($csvFileName);
    readfile('sesshokusha.csv');

  }catch(Exception $e) {

    // 例外処理をここに書きます
    echo $e->getMessage();

  }
?>

