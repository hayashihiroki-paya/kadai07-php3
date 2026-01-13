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

// ソート済みのユーザーデータを取得
$userPoints = getUserSortedPoints($userID);
// 本の全部のデータを取得
$rawBookData = getBookPoints();
// isbnごとに情報をまとめる
$books = groupBookPointsByIsbn($rawBookData);

// ユーザーのお気に入りポイントと本のデータをすり合わせて評価値を計算する
$recommendations = calculateRecommendationScores($userPoints, $books);

// すでにお気に入り登録済みの本のisbn情報をまとめる
$userFavoriteIsbn = getUserFavoriteIsbnList($userID);
// isbnがキーとなるように変換
$favoriteMap = array_flip($userFavoriteIsbn);

$filtered = []; // ダブったものを除いたリストを格納する
foreach ($recommendations as $rec) {
    // 評価値計算リストrecommendationsのisbnと、ユーザー登録済みisbnが一致したら処理を飛ばす
    if (isset($favoriteMap[$rec['isbn']])) continue;
    // ダブってないのでリストに追加
    $filtered[] = $rec;
}
// 上書きする
$recommendations = $filtered;

// isbn一覧を作る
$isbnList = array_column($recommendations, 'isbn');

// 本の表示情報を取得
$bookInfoList = getBookInfoByIsbnList($isbnList);

// isbn をキーに変換
$bookInfoMap = [];
foreach ($bookInfoList as $info) {
    $bookInfoMap[$info['isbn']] = $info;
}

// 表示用データにまとめる
$viewData = [];
foreach ($recommendations as $rec) {
    $isbn = $rec['isbn'];

    if (!isset($bookInfoMap[$isbn])) continue;

    $viewData[] = [
        'isbn'  => $isbn,
        'title' => $bookInfoMap[$isbn]['title'],
        'largeImageUrl' => $bookInfoMap[$isbn]['largeImageUrl'],
        'score' => $rec['score'],
        'reason' => $rec['reason']
    ];
}

echo json_encode($viewData, JSON_UNESCAPED_UNICODE);

// echo json_encode($recommendations, JSON_UNESCAPED_UNICODE);