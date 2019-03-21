<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「プロフィール編集「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');


//ユーザー情報を取得する
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：' .print_r($dbFormData, true));



if(!empty($_POST)) {
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST, true));
    debug('画像情報：'.print_r($_FILES, true));
    //変数定義
    $name = (!empty($_POST['name'])) ? $_POST['name']: null;
    $age = (isset($_POST['age'])) ? (int)$_POST['age']: null;
    $email = $_POST['email'];

    //画像が未選択の場合、データベースの情報を入れる
    $pic = (!empty($_FILES['pic']['name'])) ? 'img/'.$_FILES['pic']['name']: $dbFormData['pic'];
    //画像アップロード
    move_uploaded_file($_FILES['pic']['tmp_name'], $pic);



    //データベースとフォームの値が異なる場合に、バリデーションチェックを行う
    if($dbFormData['name'] !== $name) {
        //最大文字数チェック
        validMaxLen($name, 'name');
    }

    if($dbFormData['age'] != $age) {
        //数値チェック
        validNumber($age, 'age');
    }

    if($dbFormData['email'] !== $email) {
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
                ':user_id' => $dbFormData['id']
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
                    <form method="post" enctype="multipart/form-data">
                        <div class="area-msg">
                            <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                        </div>
                        <label>
                            名前
                            <input type="text" name="name"
                                value="<?php echo getFormData('name') ?>"
                                class="<?php if(!empty($error_msg['name'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['name'])) echo $error_msg['name']?>
                        </div>
                        <label>
                            年齢
                            <input type="number" name="age"
                                value="<?php echo getFormData('age')?>"
                                class="<?php if(!empty($error_msg['age'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['age'])) echo $error_msg['age']?>
                        </div>
                        <label>
                            Email
                            <input type="text" name="email"
                                value="<?php echo getFormData('email')?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        プロフィール画像
                        <label class="area-drop">
                            <input type="file" name="pic" class="js-input-file"
                                value="<?php echo getFormImageData('pic')?>"
                                class="<?php if(!empty($error_msg['pic'])) echo 'error'?>">
                            <img src="<?php echo getFormImageData('pic')?>"
                            alt=""
                            class="pre-img">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pic'])) echo $error_msg['pic']?>
                        </div>
                        <div class="btn-container">
                            <input type="submit" name="" value="変更する" class="btn btn-mid">
                        </div>
                    </form>
                </section>
                <?php
                require('sidebar.php');
                 ?>
            </div>

        </main>

        <?php require('footer.php') ?>
    </body>
</html>
