<?php
// display_errorsをONに設定
ini_set('display_errors',1);

ini_set('log_errors', 1);
ini_set('error_log', 'php.log');
// 全てのエラーを表示
error_reporting(E_ALL);






if(!empty($_POST)) {
    var_dump($_POST);
    error_log('テスト');
    //変数を定義
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $re_pass = $_POST['re_password'];

    //バリデーションチェック


    try {
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
        $sql = 'INSERT INTO users (email, password, login_time, create_date) VALUES (:email, :pass, :login_time, :create_date)';
        $data = [
            ':email' => $email,
            ':pass' => $pass,
            ':login_time' => date('Y-m-d H:i:s'),
            ':create_date' => date('Y-m-d H:i:s')
            ];

        //プリペアーステートメントを作成
        $stmt = $dbh->prepare($sql);
        //流し込んでDB実行
        $result = $stmt->execute($data);

        //成功したらmypageへ飛ばす
        if($result) {
            //header('Location: mypage.php');
        } else {
            errors('DBエラー');
        }

    } catch (Exception $e) {
        error_log('例外発生：'.$e->getMessage());
    }




    //mypageへ飛ばす
}





$title = '登録ページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents" class="site-width">
            <div class="form-container">
                <form method="post">
                    <h2 class="title">ユーザー登録</h2>
                    <label for="">
                        Email
                        <input type="text" name="email" value="">
                    </label>
                    <label for="">
                        パスワード
                        <input type="text" name="password" value="">
                    </label>
                    <label for="">
                        パスワード(再入力)
                        <input type="text" name="re_password" value="">
                    </label>
                    <input type="submit" name="" value="登録する" class="btn btn-mid">
                </form>
            </div>

        </main>
        <?php require('footer.php') ?>

    </body>
</html>
