<?php
include('functions.php');
// 文字列を受け取って改行文字などをスペースに置き換える関数
function sanitizeForCsv($str)
{
  return str_replace(["\r\n", "\r", "\n"], " ", $str);
}

session_start();
// echo "お気に入り登録ページに移動できました";
// $_POST["bookData"]ない、またはログイン情報ない時終了
if (!isset($_POST["bookData"]) || !isset($_SESSION["userID"])) {
  exit();
}

// 受け取ったデータ格納
$bookData = $_POST["bookData"];
// echo $bookData["itemCaption"];
$bookData["itemCaption"] = sanitizeForCsv($bookData["itemCaption"]);
// echo $bookData["itemCaption"];
$userID = $_SESSION["userID"];

// 重複チェック
// SQL接続
$pdo = connect_db();
$sql = 'SELECT * FROM favorites_table';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $record) {
  if ($record["userID"] === $userID && $record["isbn"] === $bookData["isbn"]) {
    // userID と isbn が重複しているので保存処理をカット
    echo "重複を検知したので保存せずに戻します";
    // indexに戻す
    // header("Location: ../index.php");
    exit();
  }
}


// SQL作成&実行
$sql = 'INSERT INTO favorites_table (id, userID, author, authorKana, isbn, itemCaption, largeImageUrl, publisherName, salesDate, seriesName, title, titleKana, comment, created_at, updated_at) VALUES (NULL, :userID, :author, :authorKana, :isbn, :itemCaption, :largeImageUrl, :publisherName, :salesDate, :seriesName, :title, :titleKana, :comment, now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
$stmt->bindValue(':author', $bookData["author"], PDO::PARAM_STR);
$stmt->bindValue(':authorKana', $bookData["authorKana"], PDO::PARAM_STR);
$stmt->bindValue(':isbn', $bookData["isbn"], PDO::PARAM_STR);
$stmt->bindValue(':itemCaption', $bookData["itemCaption"], PDO::PARAM_STR);
$stmt->bindValue(':largeImageUrl', $bookData["largeImageUrl"], PDO::PARAM_STR);
$stmt->bindValue(':publisherName', $bookData["publisherName"], PDO::PARAM_STR);
$stmt->bindValue(':salesDate', $bookData["salesDate"], PDO::PARAM_STR);
$stmt->bindValue(':seriesName', $bookData["seriesName"], PDO::PARAM_STR);
$stmt->bindValue(':title', $bookData["title"], PDO::PARAM_STR);
$stmt->bindValue(':titleKana', $bookData["titleKana"], PDO::PARAM_STR);
$stmt->bindValue(':comment', $bookData["comment"], PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}