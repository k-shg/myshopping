<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「マイページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');

$user_id = $_SESSION['user_id'];

//出品した商品一覧を取得
$my_product_list = getMyProductList($user_id);
debug('登録商品一覧を取得');




$title = 'マイページ';
require('head.php') ?>
    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">マイページ</h1>
                <section id="main" class="products-container">
                    <div class="panel-list">
                        <h2 class="title">登録商品一覧</h2>
                        <?php foreach ($my_product_list as $key => $value):?>
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
                </section>
                <?php require('sidebar.php') ?>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
