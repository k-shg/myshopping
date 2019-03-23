<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「トップページ「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

dump($_GET);

//現在のページを取得
$currentPage = (!empty($_GET['page']))? ($_GET['page']): 1;

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


//カテゴリーデータを取得
$categoryData = getCategory();

//リクエストされたカテゴチーを取得
$category = (!empty($_GET['category_id']))? ($_GET['category_id']): '';
debug('選択されたカテゴリー：'.$category);

//リクエストされた表示順を取得
$order = (!empty($_GET['order']))? ($_GET['order']): '';
debug('選択された順序：'.$category);

//商品一覧を取得
$productList = getProductList($offset_num, $category, $order);
debug('商品一覧を取得');
//debug('商品一覧データ：'.print_r($productList, true));




//ページネーション表示数
$pageColNum = 5;
//総ページ数
$totalPageNum = ceil($productList['total'] / 20);

//ページネーションに使う表示ページ数を取得
$val = pagination($currentPage, $totalPageNum, $pageColNum);
$min_page = $val['min'];
$max_page = $val['max'];

//検索条件がついていた場合、ページネーションのリンクに条件をつなげる
$link = getConditionLink($category, $order);


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
                            <span class="total-num"><?php echo $productList['total'] ?></span>件の商品が見つかりました
                        <div class="search-right">
                            <span class="num"><?php echo $offset_num + 1?></span>-
                            <span class="num"><?php echo $offset_num + $dispay_num?></span>件/
                            <spna class="num"><?php echo $productList['total'] ?></spna>件中
                        </div>
                    </div>
                    <div class="panel-list">
                        <?php foreach ($productList['data'] as $key => $value):?>
                            <a href="productDetail.php?<?php echo $link ?>page=<?php echo $currentPage ?>&product_id=<?php echo $value['id']?>" class="panel">
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
                        <ul class="pagination-list">
                            <?php if ($currentPage != 1): ?>
                                <li class="list-item"><a href="?<?php echo $link ?>page=1"><</a></li>
                            <?php endif; ?>
                            <?php for($i = $min_page; $i <= $max_page; $i++ ): ?>
                            <li class="list-item <?php if($currentPage == $i) echo 'active' ?>"><a href="?<?php echo $link ?>page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php endfor ?>
                        <?php if ($currentPage != $totalPageNum): ?>
                            <li class="list-item"><a href="?<?php echo $link ?>page=<?php echo $totalPageNum ?>">></a></li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </section>
                <div id="sidebar">
                    <form method="get">
                        <div class="select-box">
                            <h1 class="title">カテゴリー</h1>
                            <select name="category_id" id="">
                                <option value="0"<?php if($category === '') echo 'selected' ?>>選択してください<span class="icon-select"></span></option>
                                <?php foreach ($categoryData as $key => $value): ?>
                                    <option value="<?php echo $value['id'] ?>"<?php if($category == $value['id']) echo 'selected' ?>><?php echo $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="select-box">
                            <h1 class="title">表示順</h1>
                            <select name="order" id="">
                                <option value="0"<?php if($order == 0 )echo 'selected' ?>>選択してください</option>
                                <option value="1"<?php if($order == 1 )echo 'selected' ?>>金額が高い順</option>
                                <option value="2"<?php if($order == 2 )echo 'selected' ?>>金額が安い順</option>
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
