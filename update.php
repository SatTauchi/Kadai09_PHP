<?php
// エラーメッセージを表示する
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. POSTデータ取得
$date = isset($_POST["date"]) ? $_POST["date"] : '';
$fish = isset($_POST["fish"]) ? $_POST["fish"] : '';
$place = isset($_POST["place"]) ? $_POST["place"] : '';
$price = isset($_POST["price"]) ? $_POST["price"] : '';
$remarks = isset($_POST["remarks"]) ? $_POST["remarks"] : '';
$id = $_POST['id'];

// アップロードディレクトリの指定
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // ディレクトリが存在しない場合に作成
}

// 画像ファイルの処理
$imgFile = '';
if (isset($_FILES['imgFile']) && $_FILES['imgFile']['error'] == 0) {
    $imgFile = basename($_FILES['imgFile']['name']);
    $uploadFile = $uploadDir . $imgFile;
    if (!move_uploaded_file($_FILES['imgFile']['tmp_name'], $uploadFile)) {
        exit('Error: File upload failed');
    }
}

// データが空でないことを確認
if (empty($date) || empty($fish) || empty($place) || empty($price)) {
    exit('Error: Missing required fields');
}

// 2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

// imgFileが空の場合、既存の画像を取得して設定
if (empty($imgFile)) {
    $stmt = $pdo->prepare('SELECT photo FROM kadai08_table WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $imgFile = $row['photo'];
}

// 3. データ登録SQL作成
$stmt = $pdo->prepare('UPDATE kadai08_table
                       SET date = :date,
                           fish = :fish,
                           place = :place, 
                           price = :price, 
                           remarks = :remarks, 
                           photo = :photo
                       WHERE id = :id');

// 4. バインド変数を設定
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':fish', $fish, PDO::PARAM_STR);
$stmt->bindValue(':place', $place, PDO::PARAM_STR);
$stmt->bindValue(':price', $price, PDO::PARAM_INT);
$stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
$stmt->bindValue(':photo', $imgFile, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

// 5. 実行
$status = $stmt->execute();

// 6. データ登録処理後
if ($status === false) {
    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    sql_error($stmt);
} else {
    // 7. select.phpへリダイレクト
    redirect('data_list.html');
}
?>
