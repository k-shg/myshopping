<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「トップページ「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//現在のページを取得
$currentPage = (!empty($_GET['page_id']))? ($_GET['page_id']): 1;

if(!(int)$currentPage) {
    debug('不正な値が入力されました');
    $_GET = [];
    header('Location: index.php');
}
//表示件数
$dispay_num = 20;
//offset値を取得
$offset_num = ($currentPage -1) * $dispay_num;
//最大表示数
$max_item_num = ($currentPage * $dispay_num);
debug('OFFEST値：'.$offset_num);
debug('表示最大数：'.$max_item_num);


//カテゴリーを取得
$categoryData = getCategory();




//商品一覧を取得
$productList = getProductList($offset_num);
debug('商品一覧を取得');
//debug('商品一覧データ：'.print_r($productList, true));


$title = 'トップページ';
require('head.php') ?>



    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">トップページ</h1>
                <section id="main" class="products-container" style="float:right; margin-left: 20px;">
                    <div class="search-title">
                        <div class="search-left"></div>
                        <div class="search-right"></div>
                    </div>
                    <div class="panel-list">
                        <?php foreach ($productList as $key => $value):?>
                            <a href="productDetail.php?product_id=<?php echo $value['id']?>" class="panel">
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
                    <div class="pagination">

                    </div>
                </section>
                <div id="sidebar">
                    <form method="get">
                        <div class="select-box">
                            <h1 class="title">カテゴリー</h1>
                            <select name="category_id" id="">
                                <option value="0">選択してください<span class="icon-select"></span></option>
                                <?php foreach ($categoryData as $key => $category): ?>
                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="select-box">
                            <h1 class="title">表示順</h1>
                            <select name="order" id="">
                                <option value="0">選択してください</option>
                                <option value="1">金額が高い順</option>
                                <option value="2">金額が安い順</option>
                            </select>
                        </div>
                        <input type="submit" name="" value="検索">
                    </form>
                </div>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
