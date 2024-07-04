<?php
require_once('funcs.php');

// 1. POSTデータ取得
$id = isset($_POST['id']) ? $_POST['id'] : '';

if (empty($id)) {
  echo json_encode(['error' => 'Invalid ID']);
  exit;
}

// 2. DB接続します
try {
  $pdo = new PDO('mysql:dbname=gs_db_class;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  echo json_encode(['error' => 'DBConnectError:'.$e->getMessage()]);
  exit;
}

// 3. データ削除SQL作成
$stmt = $pdo->prepare("DELETE FROM kadai08_table WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// 4. データ削除処理後
if ($status==false) {
  $error = $stmt->errorInfo();
  echo json_encode(['error' => 'ErrorQuery:'.$error[2]]);
} else {
  echo json_encode(['success' => true]);
}
