<?php
include('functions.php');
session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (
    !isset($_SESSION["userID"]) || $_SESSION["userID"] === "" ||
    !isset($_POST["isbn"]) || $_POST["isbn"] === "" ||
    !isset($_POST["comment"])
) {
    // echo "ログイン情報がありません";
    exit();
}
$userID = $_SESSION["userID"];
$isbn = $_POST["isbn"];
$comment = $_POST["comment"];

$pdo = connect_db();
// SQL接続
// isbnとユーザーIDが一致しているものを削除
$sql = 'UPDATE favorites_table SET comment = :comment, updated_at = now() WHERE isbn = :isbn AND userID = :userID';
$stmt = $pdo->prepare($sql);

// バインド変数
$stmt->bindValue(':isbn', $isbn, PDO::PARAM_STR);
$stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
