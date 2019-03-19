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

if($_SESSION['login_limit'] < time()) {
    debug('セッション有効期限が切れています');
    debug($_SESSION['login_limit']);
    debug(time());
    header('Location: login.php');
}



$title = 'マイページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <section id="main">
                    
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
