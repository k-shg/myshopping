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
                <?php
                require('sidebar.php');
                 ?>
            </div>

        </main>

        <?php require('footer.php') ?>
    </body>
</html>
