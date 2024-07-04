<?php
require_once('funcs.php');

// 1. DB接続します
try {
  $pdo = new PDO('mysql:dbname=gs_db_class;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

// 2. データ取得SQL作成
$fish = isset($_GET['fish']) ? $_GET['fish'] : '';

if ($fish) {
    $stmt = $pdo->prepare("SELECT * FROM kadai08_table WHERE fish = :fish");
    $stmt->bindValue(':fish', $fish, PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("SELECT * FROM kadai08_table");
}

$status = $stmt->execute();

// 3. データ表示
if ($status==false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error" => "ErrorQuery:".$error[2]]);
  exit;
} else {
  $result = [];
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $result[] = $row;
  }
  echo json_encode($result);
}
