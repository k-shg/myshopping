<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「退会ページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


//セッションにユーザーIDがあるか確認
if(empty($_SESSION['user_id'])) {
    debug('未ログインユーザーです');
    header('Location: login.php');
}

debug('セッション情報：'.print_r($_SESSION, true));
if($_SESSION['login_limit'] < time()) {
    debug('セッション有効期限が切れています');
    header('Location: login.php');
}


if(!empty($_POST)) {

    try {
        debug('DB接続します');

        //DB接続
        $dbh = dbConnect();
        //クエリ発行
        $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :user_id';
        $data = [
            ':user_id' => $_SESSION['user_id'],
            ];
        //クエリ実行
        $stmt = postQuery($dbh, $sql, $data);

        //クエリが成功したらログインページに飛ばす
        if($stmt) {
            debug('クエリ成功');
            //セッション変数を削除する
            $_SESSION = [];
            header('Location: login.php');
        } else {
            debug('クエリ失敗');
            $error_msg['common'] = MGS_DB;
        }
    } catch (Exception $e) {
        error_log('例外発生：'.$e->getMessage());
        $error_msg['common'] = MGS_DB;
    }
}


$title = '退会ページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents" class="site-width">
            <div class="form-container">
                <form class="form" method="post" style="text-align: center;">
                    <h2 class="site-title">退会しますか？</h2>
                    <input type="submit" name="withdraw" value="退会する" class="btn btn-mid" style="float:none;">
                </form>
            </div>

        </main>
        <?php require('footer.php') ?>

    </body>
</html>
