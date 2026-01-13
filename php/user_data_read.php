<?php
include('functions.php');
session_start();
// echo "ユーザーデータ取得処理開始";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"]) || $_SESSION["userID"] === "") {
    // echo json_encode("ログイン情報なし", JSON_UNESCAPED_UNICODE);
    exit();
}

$userID = $_SESSION["userID"];

$sortData = getUserSortedPoints($userID);

// // ユーザーデータを全権取得
// $goodPoints = getUserPoints($userID);

// // 多い順にソートしてcountを追加した配列を取得
// $sortData = dataSort($goodPoints);

echo json_encode($sortData, JSON_UNESCAPED_UNICODE);
