<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「プロフィール編集「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');


dump($_POST);
if(!empty($_POST)) {
    debug('POST送信があります');
    //変数定義
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $pic = $_POST['pic'];

    //バリデーション開始

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





$title = 'プロフィール編集';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">プロフィール編集</h1>
                <section id="main" class="form-container">
                    <form method="post">
                        <div class="area-msg">
                            <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                        </div>
                        <label>
                            名前
                            <input type="text" name="name"
                                value="<?php if(!empty($email)) echo $email?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        <label>
                            年齢
                            <input type="number" name="age"
                                value="<?php if(!empty($email)) echo $email?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        <label>
                            Email
                            <input type="text" name="email"
                                value="<?php if(!empty($email)) echo $email?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pass'])) echo $error_msg['pass']?>
                        </div>
                        <label class="area-drop">
                            プロフィール画像
                            <input type="file" name="pic" class="input-file"
                                value="<?php if(!empty($email)) echo $email?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        <input type="submit" name="" value="変更する" class="btn btn-mid">
                    </form>
                </section>
                <section id="sidebar">
                    <a href="#">商品を出品する</a>
                    <a href="profEdit.php">プロフィール編集</a>
                    <a href="withdraw.php">退会</a>
                </section>
            </div>

        </main>

        <?php require('footer.php') ?>
    </body>
</html>
