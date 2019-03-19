<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「マイページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');


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
