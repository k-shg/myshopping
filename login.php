<?php
require('function.php');


if(!empty($_POST)) {

    //変数を定義
    $email = $_POST['email'];
    $pass = $_POST['password'];

    //バリデーション開始
    $error_msg = validation($email, $pass);


    if(empty($error_msg)) {
        try {
            debug('DB接続します');

            //DB接続
            $dbh = dbConnect();

            //クエリ発行
            $sql = 'SELECT * FROM users WHERE email = :email AND password = :pass';
            $data = [
                ':email' => $email,
                ':pass' => $pass,
                //':login_time' => date('Y-m-d H:i:s'),
                ];
            //クエリ実行
            $stmt = postQuery($dbh, $sql, $data);

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
