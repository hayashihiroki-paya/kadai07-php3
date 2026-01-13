<?php
// ユーザーIDを受け取って、ユーザーごとのお気に入りポイントを配列にして返します
include('functions.php');
session_start();
// echo "お気に入りデータ取得ページに移動できました";
// ログイン情報ない時終了
if (!isset($_SESSION["userID"]) || $_SESSION["userID"] === "") {
    // echo "ログイン情報がありません";
    exit();
}
$userID = $_SESSION["userID"];

// ユーザーIDからお気に入り登録済みデータを取得する
$favorites = getUserFavoriteData($userID);

echo json_encode($favorites, JSON_UNESCAPED_UNICODE);
