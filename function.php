<?php
// display_errorsをONに設定
ini_set('display_errors',1);

ini_set('log_errors', 1);
ini_set('error_log', 'php.log');
// 全てのエラーを表示
error_reporting(E_ALL);


// ===========================
//　セッション
//============================

debug('セッション開始');
debug('ーーーーーーーーーーーセッションチェックーーーーーーーーーーーーーーーー');
session_start();
session_regenerate_id();

debug('現在のセッションID：'.session_id());
debug('セッション情報：'.print_r($_SESSION, true));





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
const MSG_UNDER255 = '255文字以下で入力してください';
const MSG_HALF_ALPHANUMERIC = '半角英数字で入力してください';
const MSG_RETYPE = 'パスワード(再入力)が一致しません';
const MGS_DB = 'データベースにエラーが発生しました。';
const MSG_LOGIN = ' メールアドレスまたはパスワードが違います';
const MSG_INT = '数値で入力してください';




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

function validRequired($str, $key) {
    global $error_msg;

    //入力チェック
    if(empty($str)) {
        $error_msg[$key] = MSG_EMPTY;
    }
    return $error_msg;
}

function validEmail($str, $key) {
    global $error_msg;

    if(!filter_var($str, FILTER_VALIDATE_EMAIL)) {
        $error_msg[$key] = MSG_EMAIL;
    }
}

function validLength($str, $key) {
    global $error_msg;

    if(strlen($str) < 6 ) {
        $error_msg[$key] = MSG_OVER6;
    } else if(strlen($str) > 255) {
        $error_msg[$key] = MSG_UNDER255;
    }
}

function validHalfAlpha($str, $key) {
    global $error_msg;

    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        $error_msg[$key] = MSG_HALF_ALPHANUMERIC;
    }
}

function validMatch($pass, $re_pass) {
    global $error_msg;
    if($pass !== $re_pass) {
        $error_msg['pass'] = MSG_RETYPE;
    }
}
function validMaxLen($str, $key) {
    global $error_msg;
    if(strlen($str) > 255) {
         $error_msg[$key] = MSG_UNDER255;
    }
}

function validNumber($num, $key) {
    global $error_msg;
    if(!is_int($num)) {
        $error_msg[$key] = MSG_INT;
    }
}


// ===========================
//　データベースへ接続
//============================

function dbConnect() {
    try {
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
    } catch(Exception $e) {
        debug('データベース接続エラー：'.$e->getMessage());
    }
}

// ===========================
//　クエリー実行
//============================
function postQuery($dbh, $sql, $data) {
    debug('プリペアーステートメントを作成');
    //プリペアーステートメントを作成
    $stmt = $dbh->prepare($sql);


    //流し込んでDB実行
    $stmt->execute($data);
    debug('クエリー実行しました');
    return $stmt;
}



// ===========================
//　ユーザー取得
//============================
function getUser($user_id) {

    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg = 0';
    $data = [':user_id' => $user_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetch();
}


 ?>
