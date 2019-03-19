<?php
//セッションにユーザーIDがあるか確認
if(empty($_SESSION['user_id'])) {
    debug('未ログインユーザーです');
    header('Location: login.php');
} else {
    debug('ログインユーザーです');
}


//有効期限チェック
if(isset($_SESSION['login_limit'])) {
    if($_SESSION['login_limit'] < time()) {
        debug('セッション有効期限が切れています');
        debug('有効期限：'.$_SESSION['login_limit']);
        debug('現在時刻'.time());
        $_SESSION = [];//ここでセッション変数をリセットしないと、ログインページで無限ループになる
        header('Location: login.php');
    } else {
        debug('セッション有効期限内です');
    }

} else {
    debug('セッション有効期限がありません');
}


 ?>
