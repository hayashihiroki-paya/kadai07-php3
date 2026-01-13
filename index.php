<?php
session_start();

// ログインされていた時の処理
if (isset($_SESSION["userName"])) {
    // echo $_SESSION["userName"];
    // ユーザー名を取得する（表示用）
    $userName = $_SESSION["userName"];
    // ユーザーIDを取得する（お気に入りデータ操作用）
    $userID = $_SESSION["userID"];
    $favoriteText = "検索結果から登録したいものをドラッグ＆ドロップしてください";
} else {
    $userName = "ログインされてません";
    $favoriteText = "お気に入り登録をするにはログインしてください";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ラノベならべ</title>
    <!--jQueryを読み込み-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--jQuery UIを読み込み-->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet"
        href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-darkness/jquery-ui.css">
    <!-- axiosライブラリの読み込み -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <h1 class="title">📚ラノベならべ📚</h1>
    <p class="titleCaption">好きなラノベを集めて並べてあなただけのバーチャル本棚を作ろう！</p>
    <div id="interface">
        <div id="search">
            <input id="searchWord" type="text" class="textInput">
            <button id="searchButton" class="button">検索</button>
            <div id="numberOfMatches"></div>
        </div>
        <div id="user">
            <div id="loginUserName">ログイン中ユーザー名：<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></div>

            <div class="userData">
                <form action="./php/login.php" method="POST">
                    <select name="userID" id="userID">
                        <option value="user01">ユーザー１</option>
                        <option value="user02">ユーザー２</option>
                        <option value="user03">ユーザー３</option>
                    </select>
                    <button type="submit" class="button">ログイン</button>
                </form>
                <button class="userDataButton button">ユーザー情報を表示する</button>
            </div>

        </div>
    </div>

    <div id="main">
        <div id="leftView">
            <div id="userInformation">
                
            </div>
            <div id="detailedInformation">

            </div>
            <div id="view">

                <div id="result">

                </div>
            </div>
        </div>

        <div id="favorite">
            <p>お気に入り登録リスト</p>
            <p><?= $favoriteText ?></p>
            <div id="bookList"></div>
        </div>
    </div>

    <script type="module" src="./js/main.js"></script>
</body>

</html>