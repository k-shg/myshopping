<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「商品登録「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');

//カテゴリーを取得

$categoryData = getCategory();
debug('カテゴリー情報：'.print_r($categoryData, true));


//クエリパラメータの有無をチェック
if(!empty($_GET)) {
    //あり→商品編集ページ

    //商品IDから商品情報を取得する
    $product_id = $_GET['product_id'];

    //POST送信がある

    //データベースとフォームの値が異なる場合に、バリデーションチェックを行う

    //DBに登録





} else {
    //なし→新規登録ページ

    //POST送信がある
    if(!empty($_POST)) {
        debug('POST送信があります');
        debug('POST情報：'.print_r($_POST, true));
        debug('画像情報：'.print_r($_FILES, true));

        //変数定義
        $name = $_POST['name'];
        $category_id = (int)$_POST['category'];
        $comment = (isset($_POST['comment'])) ? $_POST['comment']: null;
        $price = (isset($_POST['price']))?  (int)$_POST['price']: null;

        $pic = (isset($_FILES['pic']['name'])) ? 'img/'.$_FILES['pic']['name']: null;

        //画像アップロード
        move_uploaded_file($_FILES['pic']['tmp_name'], $pic);


        //バリデーションチェック

        //最大文字数チェック
        validMaxLen($name, 'name');//必須項目
        if(!empty($comment)) {
            validMaxLen($comment, 'comment');
        }
        dump($price);
        //数値チェック
        validNumber($price, 'price');//必須項目


        //空文字チェック
        validRequired($name, 'name');
        validRequired($category_id, 'category');
        if($price != 0) {
            validRequired($price, 'price');
        }

        dump($category_id);


        if(empty($error_msg)) {
            try {
                debug('DB接続します');

                //DB接続
                $dbh = dbConnect();

                //クエリ発行
                $sql = 'INSERT INTO product (name, price, comment, category_id, user_id, create_date) VALUES (:name, :price, :comment, :category_id, :user_id, :create_date)';
                $data = [
                    ':name' => $name,
                    ':price' => $price,
                    ':comment' => $comment,
                    ':category_id' => $category_id,
                    ':user_id' => $_SESSION['user_id'],
                    ':create_date' => date('Y-m-d H:i:s')
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

        //DBに登録



}




$title = '商品登録';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">商品登録</h1>
                <section id="main" class="form-container">
                    <form method="post" enctype="multipart/form-data" style="width: 100%;">
                        <div class="area-msg">
                            <?php if(!empty($error_msg['common'])) echo $error_msg['common']?>
                        </div>
                        <label>
                            商品名<span class="required">必須</span>
                            <input type="text" name="name"
                                value="<?php
                                if(!empty($name)){
                                    echo $name;
                                }
                                elseif(!empty($product['name'])) {
                                    echo $product['name'];
                                }
                                ?>"
                                class="<?php if(!empty($error_msg['name'])) echo 'error'?>">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['name'])) echo $error_msg['name']?>
                        </div>
                        <label>
                            カテゴリー<span class="required">必須</span>
                            <select name="category" id="">
                                <option value="0">選択してください</option>
                                <?php foreach ($categoryData as $key => $category): ?>
                                <option value="<?php echo $category['id'] ?>"
                                    <?php if(!empty($category_id)  &&$category_id === $category['id'] ) echo 'selected';?>>
                                    <?php echo $category['name'] ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['category'])) echo $error_msg['category']?>
                        </div>

                        <label>
                            金額<span class="required">必須</span>
                            <div class="form-group">
                                <input type="number" name="price"
                                    value="<?php if(isset($price)) echo $price?>"
                                    class="<?php if(!empty($error_msg['price'])) echo 'error'?>">
                                    <span class="yen">円</span>
                            </div>
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['price'])) echo $error_msg['price']?>
                        </div>
                        <label>
                            詳細
                            <textarea name="comment" class="<?php if(!empty($error_msg['comment'])) echo 'error'?>" style="height: 150px;"><?php
                            if(!empty($comment)){
                                echo $comment;
                            }elseif(!empty($product['comment'])) {
                                echo $product['comment'];
                            }
                            ?></textarea>
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['comment'])) echo $error_msg['comment']?>
                        </div>
                        商品画像
                        <label class="area-drop">
                            <input type="file" name="pic" class="js-input-file"
                                value=""
                                class="<?php if(!empty($error_msg['pic'])) echo 'error'?>">
                            <img src="<?php

                            //フォームにデータがあるとき
                            if(!empty($pic) ){
                                debug('two');
                                echo $pic;
                            }//データベースに画像があるとき
                            elseif(!empty($product['pic'])) {
                                debug('one');
                                echo $product['pic'];
                            }
                             ?>"
                            alt=""
                            class="pre-img">
                        </label>
                        <div class="area-msg">
                            <?php if(!empty($error_msg['pic'])) echo $error_msg['pic']?>
                        </div>
                        <div class="btn-container">
                            <input type="submit" name="" value="出品する" class="btn btn-mid">
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
