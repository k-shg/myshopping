<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「マイページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');

//ユーザーIDを取得
$user_id = $_SESSION['user_id'];
//セッションメッセージを取得
$message = $_SESSION['message'];
$_SESSION['message'] = '';

//出品した商品一覧を取得
$sale_product_list = getMySellingProducts($user_id);
debug('登録商品一覧を取得');

//購入した商品一覧を取得
$buy_product_list = getMyHavingProducts($user_id);
debug('購入商品一覧を取得');


$title = 'マイページ';
require('head.php') ?>


    <body>
        <style media="screen">
            .table {
                width: 100%;
                border-spacing: 0px 7px;
            }
            .list-table .table td {
                background: #f6f5f4;
            }
        </style>
        <div class="flash-msg js-flash-msg">
            <?php if(!empty($message)) echo $message; ?>
        </div>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">マイページ</h1>

                <section id="main" class="products-container">
                    <div class="list panel-list">
                        <h2 class="horizon-container">
                            <span class="title">登録商品一覧</span>
                        </h2>
                        <?php foreach ($sale_product_list as $key => $value):?>
                        <a href="registProduct.php?product_id=<?php echo $value['id']?>" class="panel">
                            <div class="panel__head">
                                <img class="panel__img" src="<?php echo (!empty($value['pic']))? $value['pic']: 'img/Noimage_image.png'; ?>" alt="">
                                <div class="panel-cover">
                                    <i class="fas fa-link panel__icon-link"></i>
                                </div>
                            </div>
                            <div class="panel__body">
                                <?php echo $value['name'] ?>
                                <span class="price panel__price">¥<?php echo $value['price'] ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="list panel-list">
                        <h2 class="horizon-container">
                            <span class="title">購入商品一覧</span>
                        </h2>
                        <?php foreach ($buy_product_list as $key => $value):?>
                        <a class="panel" href="productDetail.php?product_id=<?php echo $value['product_id']?>">
                            <div class="panel__head">
                                <img class="panel__img" src="<?php echo (!empty($value['pic']))? $value['pic']: 'img/Noimage_image.png'; ?>" alt="">
                            </div>
                            <div class="panel__body">
                                <?php echo $value['name'] ?>
                                <span class="price">¥<?php echo $value['price'] ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php require('sidebar.php') ?>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
