<?php
//共通に使う関数を記述
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// データベース接続関数
function db_conn()
{
    // このプログラムを実行しているサーバー情報を取得して変数に保存
    // $_SERVERについて
    // https://www.php.net/manual/ja/reserved.variables.server.php   
    $server_info = $_SERVER;

    $db_name = "";
    $db_id = "";
    $db_pw = "";
    $db_host = "";

    // サーバー情報の中のサーバの名前がlocalhostだった場合と本番だった場合で処理を分ける
    if ($server_info["SERVER_NAME"] == "localhost") {
        // localhostの場合はこのデータを変数に代入
        $db_name = 'gs_board';       // データベース名
        $db_id   = 'root';                    // アカウント名
        $db_pw   = '';                        // パスワード：XAMPPはパスワード無し、MAMPの場合はroot
        $db_host = 'localhost';               // DBホスト
    } else {
        // localhostでない場合(本番環境)はこのデータを変数に代入
        $db_name = '';           // 本番環境のDBの名前
        $db_host = ''; // 自身のDBが割り当てられているサーバを記述
        $db_id   = '';               // さくらのアカウント
        $db_pw   = '';          // さくらのデータベースにログインする際のパスワード

    }
    try {
        // テンプレートリテラルでの書き方の場合
        $pdo = new PDO("mysql:dbname={$db_name};charset=utf8;host={$db_host}", $db_id, $db_pw);

        // $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
        return $pdo;
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//リダイレクト関数: redirect($file_name)
function redirect($file_name)
{
    header('Location: ' . $file_name);
    exit();
}

// ファイルアップロード関数
function handleFileUpload($fileFieldName)
{
    if (isset($_FILES[$fileFieldName]) && $_FILES[$fileFieldName]['error'] === UPLOAD_ERR_OK) {
        return file_get_contents($_FILES[$fileFieldName]['tmp_name']);
    } elseif ($_FILES[$fileFieldName]['error'] !== UPLOAD_ERR_NO_FILE) {
        exit('写真のアップロードに失敗しました');
    }
    return null;
}
