<?php
include('functions.php');
session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"])) {
    exit();
}

$userID = $_SESSION["userID"];

// POSTデータ受け取れるかチェック
if (
    !isset($_POST["isbn"]) ||
    !isset($_POST["title"]) ||
    !is_array($_POST["goodPoint"])
) {
    exit();
}


$isbn = $_POST["isbn"];
$title = $_POST["title"];
$goodPoint = $_POST["goodPoint"];
echo $isbn . "," . $title;
// var_dump($goodPoint); //=> 二次元配列 OK

$pdo = connect_db();
for ($i = 0; $i < count($goodPoint); $i++) {
    // SQL作成&実行
    $sql = 'INSERT INTO good_point_table (id, userID, isbn, title, category, goodPoint, created_at, updated_at) VALUES (NULL, :userID, :isbn, :title, :category, :goodPoint, now(), now())';

    $stmt = $pdo->prepare($sql);

    // バインド変数を設定
    $stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
    $stmt->bindValue(':isbn', $isbn, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':category', $goodPoint[$i][0], PDO::PARAM_STR);
    $stmt->bindValue(':goodPoint', $goodPoint[$i][1], PDO::PARAM_STR);

    // SQL実行（実行に失敗すると `sql error ...` が出力される）
    try {
        $status = $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(["sql error" => "{$e->getMessage()}"]);
        exit();
    }
}