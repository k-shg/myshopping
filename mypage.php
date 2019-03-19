<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「マイページ「「「「「「「「「「「「「「「「');
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



$title = 'マイページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents" class="site-width">
            <div class="search">


            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
