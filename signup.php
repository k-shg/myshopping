<?php
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
