<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「プロフィール編集「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');


//ユーザー情報を取得する
$user = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：' .print_r($user, true));




if(!empty($_POST)) {
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST, true));
    //変数定義
    $name = (isset($_POST['name'])) ? $_POST['name']: null;
    $age = (isset($_POST['age'])) ? (int)$_POST['age']: null;
    $email = $_POST['email'];
    $pic = $_POST['pic'];


    //データベースとフォームの値が異なる場合に、バリデーションチェックを行う
    if($user['name'] !== $name) {
        //最大文字数チェック
        validMaxLen($name, 'name');
    }

    if($user['age'] != $age) {
        //数値チェック
        validNumber($age, 'age');
    }

    if($user['email'] !== $email) {
        validEmail($email, 'email');
        validRequired($email, 'email');
    }


    if(empty($error_msg)) {
        try {
            debug('DB接続します');

            //DB接続
            $dbh = dbConnect();

            //クエリ発行
            $sql = 'UPDATE users SET name = :name, age = :age, email = :email, pic = :pic WHERE id = :user_id';
            $data = [
                ':name' => $name,
                ':age' => $age,
                ':email' => $email,
                ':pic' => $pic,
                ':user_id' => $user['id']
                ];
            //クエリ実行
            $stmt = postQuery($dbh, $sql, $data);
            if($stmt) {
                debug('DB情報を更新しました');
                //header('Location: mypage.php');
            } else {
                debug('DB情報を更新できませんでした');
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
                                value="<?php
                                if(!empty($name)){
                                    echo $name;
                                }
                                elseif(!empty($user['name'])) {
                                    echo $user['name'];
                                }
                                ?>"
                                class="<?php if(!empty($error_msg['name'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['name'])) echo $error_msg['name']?>
                        </div>
                        <label>
                            年齢
                            <input type="number" name="age"
                                value="<?php if(isset($age)) echo $age?>"
                                class="<?php if(!empty($error_msg['age'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['age'])) echo $error_msg['age']?>
                        </div>
                        <label>
                            Email
                            <input type="text" name="email"
                                value="<?php
                                if(!empty($email)){
                                    echo $email;
                                }
                                elseif(!empty($user['email'])) {
                                    echo $user['email'];
                                }
                                ?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        <label class="area-drop">
                            プロフィール画像
                            <input type="file" name="pic" class="input-file"
                                value="<?php if(!empty($pic)) echo $pic?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pic'])) echo $error_msg['pic']?>
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
