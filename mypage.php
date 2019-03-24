<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「マイページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');

$user_id = $_SESSION['user_id'];

//出品した商品一覧を取得
$sale_product_list = getMySellingProducts($user_id);
debug('登録商品一覧を取得');


//購入した商品一覧を取得
$buy_product_list = getMyHavingProducts($user_id);
// dump($buy_product_list);


//自分が関わる取引情報とメッセージを取得
$orders = getMyOrdersAndMsg($user_id);




$title = 'マイページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">マイページ</h1>
                <section id="main" class="products-container">
                    <div class="list panel-list">
                        <h2 class="title">登録商品一覧</h2>
                        <?php foreach ($sale_product_list as $key => $value):?>
                        <a href="registProduct.php?product_id=<?php echo $value['id']?>" class="panel">
                            <div class="panel-head">
                                <img src="<?php echo (!empty($value['pic1']))? $value['pic1']: 'img/Noimage_image.png'; ?>" alt="">
                            </div>
                            <div class="panel-body">
                                <?php echo $value['name'] ?>
                                <span class="price">¥<?php echo $value['price'] ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="list panel-list">
                        <h2 class="title">購入商品一覧</h2>
                        <?php foreach ($buy_product_list as $key => $value):?>
                        <a href="productDetail.php?product_id=<?php echo $value['product_id']?>" class="panel">
                            <div class="panel-head">
                                <img src="<?php echo (!empty($value['pic1']))? $value['pic1']: 'img/Noimage_image.png'; ?>" alt="">
                            </div>
                            <div class="panel-body">
                                <?php echo $value['name'] ?>
                                <span class="price">¥<?php echo $value['price'] ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="list list-table">
                        <h2 class="title">掲示板一覧</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>最新送信日時</th>
                                    <th>取引相手</th>
                                    <th>メッセージ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>◉</td>
                                    <td>◉</td>
                                    <td>◉</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                <?php require('sidebar.php') ?>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
