<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「ログインページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//セッションがあったらマイページへ飛ばす
if(isset($_SESSION['user_id'])) {
    debug('ログイン済みユーザーです');
    header('Location: mypage.php');
}



if(!empty($_POST)) {

    //変数を定義
    $email = $_POST['email'];
    $pass = $_POST['password'];

    //バリデーション開始
    validEmail($email, 'email');
    validLength($pass, 'pass');

    validHalfAlpha($pass, 'pass');

    validRequired($email, 'email');
    validRequired($pass, 'pass');

    dump($error_msg);


    if(empty($error_msg)) {
        try {
            debug('DB接続します');

            //DB接続
            $dbh = dbConnect();
            //クエリ発行
            $sql = 'SELECT * FROM users WHERE email = :email AND delete_flg = 0';

            $data = [
                ':email' => $email,
                ];
            //クエリ実行
            $stmt = postQuery($dbh, $sql, $data);

            //結果を配列で取得
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //パスワードが合っていればmypageへ飛ばす
            if(password_verify($pass, $row['password'])) {
                debug('パスワード一致');
                //セッションにユーザーidを保存
                $_SESSION['user_id'] = $row['id'];
                //セッションに現在のログイン時間を保存
                $_SESSION['login_date'] = time();
                //セッションに有効期限を保存
                $_SESSION['login_limit'] = time() + 60*60;

                header('Location: mypage.php');
            } else {
                debug('パスワード合ってない');
                $error_msg['common'] = MSG_LOGIN;
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
            <div class="form-container form-container-search">
                <form class="form" method="post">
                    <h2 class="site-title">ログイン</h2>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                    </div>
                    <label for="">
                        Email
                        <input class="input" type="text" name="email"
                            value="<?php if(!empty($email)) echo $email?>"
                            class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                    </div>
                    <label for="">
                        パスワード
                        <input class="input" type="password" name="password"
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
