<?php
// display_errorsをONに設定
ini_set('display_errors',1);

ini_set('log_errors', 1);
ini_set('error_log', 'php.log');
// 全てのエラーを表示
error_reporting(E_ALL);


// ===========================
//　グローバル変数定義
//============================
$error_msg = [];


// ===========================
//　エラーメッセージ用の定数
//============================
const MSG_EMPTY = '入力必須です';
const MSG_EMAIL = 'emailの形式で入力してください';
const MSG_OVER6 = '6文字以上で入力してください';
const MSG_UNDER255 = '255以下で入力してください';
const MSG_HALF_ALPHANUMERIC = '半角英数字で入力してください';
const MSG_RETYPE = 'パスワード(再入力)が一致しません';
const MGS_DB = 'データベースにエラーが発生しました。';
const MSG_LOGIN = ' メールアドレスまたはパスワードが違います';



// ===========================
//　ログ出力デバッグ
//============================
function debug($str) {
    error_log('デバッグ：'.$str);
}

// ===========================
//　画面出力デバッグ
//============================

function dump($str) {
    echo('<pre>');
    var_dump($str);
    echo('</pre>');
}


// ===========================
//　バリデーション
//============================
function validation($email, $pass) {
    debug('バリデーション開始');
    global $error_msg;

    //email形式チェック
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg['email'] = MSG_EMAIL;
    }

    //文字数チェック
    if(strlen($pass) < 6 ) {
        $error_msg['pass'] = MSG_OVER6;
    } else if(strlen($email) > 255) {
        $error_msg['pass'] = MSG_UNDER255;
    }

    //半角英数チェック
    if(!preg_match("/^[a-zA-Z0-9]+$/", $pass)) {
        $error_msg['pass'] = MSG_HALF_ALPHANUMERIC;
    }

    //入力チェック
    if(empty($email)) {
        $error_msg['email'] = MSG_EMPTY;
    }
    if(empty($pass)){
        $error_msg['pass'] = MSG_EMPTY;
    }
    debug('バリデーションOK');
    return $error_msg;
}

// ===========================
//　データベースへ接続
//============================

function dbConnect() {
    $dsn = 'mysql:dbname=myshopping;host=localhost;charset=utf8mb4';
    $username = 'root';
    $password = 'root';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    $dbh = new PDO($dsn, $username, $password, $options);
    return $dbh;
}

// ===========================
//　クエリー実行
//============================
function postQuery($dbh, $sql, $data) {
    //プリペアーステートメントを作成
    $stmt = $dbh->prepare($sql);

    //流し込んでDB実行
    $stmt->execute($data);
    return $stmt;
}
 ?>
