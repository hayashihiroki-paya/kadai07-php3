<?php
include('functions.php');

session_start();
// echo "ユーザーデータ取得処理開始";
// POST情報ない時終了
if (!isset($_POST["isbn"]) || $_POST["isbn"] === "") {
    exit();
}

$isbn = $_POST["isbn"];

// isbnを渡して１冊のデータを取得
$goodPoints = getBookPoints($isbn);

// データをソートして件数も追加したものに変換します
$sortData = dataSort($goodPoints);

echo json_encode($sortData, JSON_UNESCAPED_UNICODE);
