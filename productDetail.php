<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「商品詳細ページ「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');



//商品IDから商品情報を取得する
$product_id =  ($_GET) ? $_GET['product_id']: '';
$dbFormData = getProductDetail($product_id);
debug('GET情報：'.print_r($_GET, true));
debug('商品詳細情報：'.print_r($dbFormData, true));

if(!$dbFormData) {
    debug('GET送が信ないか、不正な値が入力されました');
    header('Location: index.php');
}
if(!empty($_POST['submit'])) {
    debug('購入ボタンが押されました');
    //会員登録しているユーザーのみ購入できるようにする
    require('auth.php');
    buyProduct($product_id, $_SESSION['user_id'], $dbFormData['user_id']);
    $_SESSION['message'] = '商品を購入しました';
    header('Location: mypage.php');
}


//ゲットパラメータを取得し、前回のページに条件つきで戻れるようにする
$param = appendGetParam('product_id');


$title = '商品詳細ページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="site-width">
                <h1 class="site-title">商品詳細ページ</h1>
                <section id="main" class="product">
                    <span class="product__category"><?php echo $dbFormData['category_name'] ?></span>
                    <span class="product__name"><?php echo $dbFormData['name'] ?></span>
                    <div class="product__img-container">
                        <img class="product__img" src="<?php echo $dbFormData['pic'] ?>" alt="">
                    </div>
                </section>
                <?php
                require('sidebar.php');
                 ?>
                <section class="product-comment">
                    <div class="product-comment-container">
                        <p style="text-align:center;">商品説明文</p>
                        <p class="comment"><?php echo ($dbFormData['comment']) ?></p>
                    </div>
                </section>

                <section class="product-buy">
                    <a href="index.php?<?php echo $param ?>"><span class="product-buy__item-left">>商品一覧に戻る</span></a>
                    <form method="post">
                        <input type="submit" name="submit" value="購入する" class="btn-mid product-buy__btn">
                    </form>
                    <span class="product-buy__item-right">¥<?php echo $dbFormData['price'] ?></span>
                </section>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
