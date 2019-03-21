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
    debug('GET送信ないか、不正な値が入力されました');
    header('Location: index.php');
}
dump($_POST);
if(!empty($_POST['submit'])) {
    dump('購入');
}


$title = '商品詳細ページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="site-width">
                <h1 class="site-title">商品詳細ページ</h1>
                <section id="main" class="product-container">
                    <span class="category"><?php echo $dbFormData['category_name'] ?></span>
                    <span class="product-name"><?php echo $dbFormData['name'] ?></span>
                    <img src="<?php echo $dbFormData['pic1'] ?>" alt="">
                </section>
                <?php
                require('sidebar.php');
                 ?>
                <section class="product-comment">
                    <div class="product-comment-container">
                        <p style="text-align:center;">商品説明文</p>
                        <p class="comment"><?php echo $dbFormData['comment'] ?></p>
                    </div>
                </section>

                <section class="product-buy">
                    <span class="item-left">>商品一覧に戻る</span>
                    <form method="post">
                        <input type="submit" name="submit" value="買う!" class="btn btn-primary">
                    </form>
                    <span class="item-right">¥<?php echo $dbFormData['price'] ?></span>
                </section>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
