<?php
include('functions.php');
session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"]) || $_SESSION["userID"] === "") {
    exit();
}

$userID = $_SESSION["userID"];
$isbn = $_POST["isbn"];

// SQL接続
$pdo = connect_db();
$sql = 'SELECT * FROM good_point_table';
$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$goodPoints = [];
foreach ($result as $record) {
    if ($record["userID"] === $userID && $record["isbn"] === $isbn) {
        // userID が一致しているので配列に保存
        $goodPoints[] = [
            "category" => $record["category"],
            "goodPoint" => $record["goodPoint"]
        ];
    }
}

echo json_encode($goodPoints, JSON_UNESCAPED_UNICODE);