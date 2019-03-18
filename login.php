<?php
// display_errorsをONに設定
ini_set('display_errors',1);

ini_set('log_errors', 1);
ini_set('error_log', 'php.log');
// 全てのエラーを表示
error_reporting(E_ALL);



dump($_POST);



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

function debug($str) {
    error_log('デバッグ：'.$str);
}

function dump($str) {
    echo('<pre>');
    var_dump($str);
    echo('</pre>');
}

if(!empty($_POST)) {

    //変数を定義
    $email = $_POST['email'];
    $pass = $_POST['password'];

    //バリデーション
    debug('バリデーション開始');
    $error_msg = [];

    //入力チェック
    if(empty($email)) {
        $error_msg['email'] = MSG_EMPTY;
    }
    if(empty($pass)){
        $error_msg['pass'] = MSG_EMPTY;
    }

    if(empty($error_msg)) {
        debug('入力チェックOK');
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
    }


    if(empty($error_msg)) {
        try {
            debug('DB接続します');

            //DB接続
            $dsn = 'mysql:dbname=myshopping;host=localhost;charset=utf8mb4';
            $username = 'root';
            $password = 'root';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $dbh = new PDO($dsn, $username, $password, $options);

            //クエリ発行
            $sql = 'SELECT * FROM users WHERE email = :email AND password = :pass';
            $data = [
                ':email' => $email,
                ':pass' => $pass,
                //':login_time' => date('Y-m-d H:i:s'),
                ];

            //プリペアーステートメントを作成
            $stmt = $dbh->prepare($sql);

            //流し込んでDB実行
            $stmt->execute($data);

            //結果を配列で取得
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //成功したらmypageへ飛ばす
            if($row) {
                debug('SQL処理が成功しました');
                header('Location: mypage.php');
            } else {
                debug('SQL処理が失敗しました');
            }

        } catch (Exception $e) {
            error_log('例外発生：'.$e->getMessage());
            $error_msg['common'] = MGS_DB;
        }
    }

}





$title = 'ログインページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents" class="site-width">
            <div class="form-container">
                <form method="post">
                    <h2 class="title">ログイン</h2>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                    </div>
                    <label for="">
                        Email
                        <input type="text" name="email"
                            value="<?php if(!empty($email)) echo $email?>"
                            class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                    </div>
                    <label for="">
                        パスワード
                        <input type="text" name="password"
                            value="<?php if(!empty($pass)) echo $pass?>"
                            class="<?php if(!empty($error_msg['pass'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['pass'])) echo $error_msg['pass']?>
                    </div>
                    <input type="submit" name="" value="ログイン" class="btn btn-mid">
                </form>
            </div>

        </main>
        <?php require('footer.php') ?>

    </body>
</html>
