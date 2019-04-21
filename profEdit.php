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

    //変数定義
    $name = (!empty($_POST['name'])) ? $_POST['name']: null;
    $age = (isset($_POST['age'])) ? (int)$_POST['age']: null;
    $email = $_POST['email'];
    $pass = (!empty($_POST['pass']))?  $_POST['pass']: '';
    //パスワードが入力されていれば、バリデーションチェックを行い、ハッシュ化する
    if(!empty($pass)) {
        validLength($pass, 'pass');
        validHalfAlpha($pass, 'pass');
        if(empty($error_msg['pass'])) {
            $pass = password_hash($pass, PASSWORD_BCRYPT);
        }
    } else {
        //入力がなければ元のパスワードを使う
        $pass = $dbFormData['password'];
    }


    //画像が選択されている場合、アップロード処理をしてパスを格納する
    $img_path = (!empty($_FILES['pic']['name'])) ? getUploadingImgPath($_FILES['pic'], 'pic'): '';
    //画像が未選択の場合、データベースの情報を入れる
    $img_path = (!empty($img_path)) ? $img_path: $dbFormData['pic'] ;

    //画像変更したときに画像を表示させ続けるため、セッションにファイル名を保存する
    $_SESSION['tmp_path'] = $img_path;

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
            $sql = 'UPDATE users SET name = :name, age = :age, email = :email, pic = :pic, password = :pass WHERE id = :user_id';
            $data = [
                ':name' => $name,
                ':age' => $age,
                ':email' => $email,
                ':pic' => $img_path,
                ':pass' => $pass,
                ':user_id' => $dbFormData['id']
                ];
            //クエリ実行
            $stmt = postQuery($dbh, $sql, $data);
            if($stmt) {
                debug('DB情報を更新しました');
                $_SESSION['message'] = 'プロフィールを変更しました';
                header('Location: mypage.php');

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
                    <form class="form" method="post" enctype="multipart/form-data">
                        <div class="area-msg">
                            <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                        </div>
                        <label>
                            名前
                            <input class="input" type="text" name="name"
                                value="<?php echo getFormData('name') ?>"
                                class="<?php if(!empty($error_msg['name'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['name'])) echo $error_msg['name']?>
                        </div>
                        <label>
                            年齢
                            <input class="form__input-num" type="number" name="age"
                                value="<?php echo getFormData('age')?>"
                                class="<?php if(!empty($error_msg['age'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['age'])) echo $error_msg['age']?>
                        </div>
                        <label>
                            Email
                            <input class="input" type="text" name="email"
                                value="<?php echo getFormData('email')?>"
                                class="<?php if(!empty($error_msg['email'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['email'])) echo $error_msg['email']?>
                        </div>
                        <label>
                            パスワード
                            <input type="password" name="pass"
                                value="<?php if(!empty($_POST['pass'])) echo  $_POST['pass']?>"
                                class="<?php if(!empty($error_msg['pass'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pass'])) echo $error_msg['pass']?>
                        </div>
                        プロフィール画像
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pic'])) echo $error_msg['pic']?>
                        </div>
                        <label class="area-drop"　<?php if(!empty($error_msg['pic'])) echo 'error'?>>
                            <input type="hidden" name="MAX_FILE_SIZE" value="60000">
                            <input type="file" name="pic" class="js-input-file"
                                class="<?php if(!empty($error_msg['pic'])) echo 'error'?>">
                            <img src="<?php echo getFormImageData('pic')?>" class="pre-img">
                            ドロップアンドドラッグ
                        </label>

                        <div class="btn-container">
                            <button type="submit" name="" value="変更する" class="btn btn-mid">
                                <span class="btn__text">変更する</span>
                            </button>
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
