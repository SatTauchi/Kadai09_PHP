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

// imgFileが空の場合、デフォルトの値を設定
if (empty($imgFile)) {
    $imgFile = 'default.jpg';  // デフォルトの画像ファイル名
}

// 2. DB接続します
try {
    // ID:'root', Password: xamppは 空白 ''
    $pdo = new PDO('mysql:dbname=gs_db_class;charset=utf8;host=localhost', 'root', '');
} catch (PDOException $e) {
    exit('DBConnectError:' . $e->getMessage());
}

// 3. データ登録SQL作成
$stmt = $pdo->prepare('INSERT INTO kadai08_table (id, date, fish, place, price, remarks, photo) 
                        VALUES (NULL, :date, :fish, :place, :price, :remarks, :photo)');

// 4. バインド変数を設定
$stmt->bindValue(':date', $date, PDO::PARAM_STR);
$stmt->bindValue(':fish', $fish, PDO::PARAM_STR);
$stmt->bindValue(':place', $place, PDO::PARAM_STR);
$stmt->bindValue(':price', $price, PDO::PARAM_INT);
$stmt->bindValue(':remarks', $remarks, PDO::PARAM_STR);
$stmt->bindValue(':photo', $imgFile, PDO::PARAM_STR);

// 5. 実行
$status = $stmt->execute();

// 6. データ登録処理後
if ($status === false) {
    // SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit('ErrorMessage:' . $error[2]);
} else {
    // 7. data_list.phpへリダイレクト
    header('Location: data_input.html');
}
?>
