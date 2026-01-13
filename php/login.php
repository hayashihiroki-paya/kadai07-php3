<?php
include('functions.php');
session_start();

if (!isset($_POST["userID"]) || $_POST["userID"] === "") {
    exit("userIDがありません");
}

$userID = $_POST["userID"];

// SQL接続
$pdo = connect_db();
$sql = 'SELECT * FROM users_table';
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
    if ($record["userID"] === $userID) {
        // 登録されているかを知らべ、見つけたときセッションに保存
        $_SESSION["userID"] = $record["userID"];
        $_SESSION["userName"] = trim($record["userName"]);
        // indexに戻す
        header("Location: ../index.php");
        exit();
    }
}
exit();