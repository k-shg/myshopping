<?php
require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「ユーザー登録ページ「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');




if(!empty($_POST)) {

    //変数を定義
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $re_pass = $_POST['re_password'];

    //バリデーション開始
    validEmail($email, 'email');

    validLength($pass, 'pass');
    validHalfAlpha($pass, 'pass');


    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($re_pass, 're_pass');

    validMatch($pass, $re_pass);

    if(empty($error_msg)) {
        try {
            //DB接続
            $dbh = dbConnect();

            //クエリ発行
            $sql = 'INSERT INTO users (email, password, login_time, create_date) VALUES (:email, :pass, :login_time, :create_date)';
            $data = [
                ':email' => $email,
                ':pass' => password_hash($pass, PASSWORD_BCRYPT),
                ':login_time' => date('Y-m-d H:i:s'),
                ':create_date' => date('Y-m-d H:i:s')
                ];

            //クエリ実行
            $stmt = postQuery($dbh, $sql, $data);

            //成功したらmypageへ飛ばす
            if($stmt) {
                debug('データベースを更新しました');
                //セッションにユーザーidを保存
                $_SESSION['user_id'] = $dbh->lastInsertId();
                //セッションに現在のログイン時間を保存
                $_SESSION['login_date'] = time();
                //セッションに有効期限を保存
                $_SESSION['login_limit'] = time() + 60*60;

                $_SESSION['message'] = 'ユーザー登録しました';

                header('Location: mypage.php');
            } else {
                debug('データベースを更新できませんでした');
            }

        } catch (Exception $e) {
            error_log('例外発生：'.$e->getMessage());
        }
    }

}



$title = '登録ページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents" class="site-width">
            <div class="form-container">
                <form class="form" method="post">
                    <h2 class="site-title">ユーザー登録</h2>
                    <label for="">
                        Email
                        <input 
                            type="text" name="email"
                            value="<?php if(!empty($email)) echo $email?>"
                            class="input <?php if(!empty($error_msg['email'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                    </div>
                    <label for="">
                        パスワード
                        <input type="password" name="password"
                            value="<?php if(!empty($pass)) echo $pass?>"
                            class="<?php if(!empty($error_msg['pass'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['pass'])) echo $error_msg['pass']?>
                    </div>
                    <label for="">
                        パスワード(再入力)
                        <input type="password" name="re_password"
                            value="<?php if(!empty($re_pass)) echo $re_pass?>"
                            class="<?php if(!empty($error_msg['re_pass'])) echo 'error'?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($error_msg['re_pass'])) echo $error_msg['re_pass']?>
                    </div>
                    <input type="submit" name="" value="登録する" class="btn btn-mid">
                </form>
            </div>

        </main>
        <?php require('footer.php') ?>

    </body>
</html>
