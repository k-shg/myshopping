<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「トップページ「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

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

//検索キーワードを取得
$search_keyword = (!empty($_GET['search_name']))? ($_GET['search_name']): '';

//カテゴリーデータを取得
$categoryData = getCategory();

//リクエストされたカテゴチーを取得
$category = (!empty($_GET['category_id']))? ($_GET['category_id']): '';
debug('選択されたカテゴリー：'.$category);

//リクエストされた表示順を取得
$order = (!empty($_GET['order']))? ($_GET['order']): '';
debug('選択された順序：'.$category);

//商品一覧を取得
$productList = getProductList($offset_num, $category, $order, $search_keyword);
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


$title = 'トップページ';
require('head.php') ?>

    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <div id="search">
                    <form class="form search__form" method="get">
                        <div class="search__select-box">
                            <input
                            value="<?php echo getFormData('search_name', true) ?>"
                            class="search__input" type="text"
                            name="search_name" placeholder="キーワードから探す">
                        </div>
                        <div class="search__select-box">
                            カテゴリー：
                            <select name="category_id" class="search__select">
                                <option class="search__item" value="0"<?php if($category === '') echo 'selected' ?>>選択してください<span class="icon-select"></span></option>
                                <?php foreach ($categoryData as $key => $value): ?>
                                    <option value="<?php echo $value['id'] ?>"<?php if($category == $value['id']) echo 'selected' ?>><?php echo $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="search__select-box">
                            表示順：
                            <select name="order" class="search__select">
                                <option class="search__item" value="0"<?php if($order == 0 )echo 'selected' ?>>選択してください</option>
                                <option class="search__item" value="1"<?php if($order == 1 )echo 'selected' ?>>金額が高い順</option>
                                <option class="search__item" value="2"<?php if($order == 2 )echo 'selected' ?>>金額が安い順</option>
                            </select>
                        </div>
                        <button type="submit" class="search__button">
                            <i class="fas fa-search-plus"></i>
                            <span class="search__button-title">検索</span>
                        </button>
                    </form>
                </div>
                <div class="horizon-container"></div>
                <section class="products-container">
                    <div class="search-title">
                        <div class="search-left"></div>
                            <span class="total-num"><?php echo $productList['total'] ?></span>件の商品が見つかりました
                        <div class="search-right">
                            <span class="num"><?php echo $offset_num + 1?></span>-
                            <!-- 合計商品数が表示数(20)よりが少ない場合、合計商品数を表示する -->
                            <span class="num"><?php echo (($offset_num + $dispay_num) < $productList['total'])? $offset_num + $dispay_num : $productList['total']; ?></span>件/
                            <spna class="num"><?php echo $productList['total'] ?></spna>件中
                        </div>
                    </div>
                    <div class="panel-list">
                        <?php foreach ($productList['data'] as $key => $value):?>
                            <a class="panel panel-index" href="productDetail.php?<?php echo (!empty(appendGetParam()))? appendGetParam().'&': '';?>product_id=<?php echo $value['id']?>">
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
                    <div class="pagination">
                        <ul class="pagination-list">
                            <?php if ($currentPage != 1): ?>
                                <!-- appendGetParam['page']　=  page以外の他のパラメータ条件を保持。&でパラメータをつなげてリンクをつくる -->
                                <!-- appendGetParam()　GET情報がなければ空文字を出力。ページネーションの数字のみでリンクを作る -->
                                <li class="list-item"><a href="?<?php echo (!empty(appendGetParam()))? appendGetParam('page').'&': '';?>page=1"><</a></li>
                            <?php endif; ?>
                            <?php for($i = $min_page; $i <= $max_page; $i++ ): ?>
                            <li class="list-item <?php if($currentPage == $i) echo 'active' ?>"><a href="?<?php echo (!empty(appendGetParam()))? appendGetParam('page').'&': '';?>page=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php endfor ?>
                        <?php if ($currentPage != $totalPageNum): ?>
                            <li class="list-item"><a href="?<?php echo (!empty(appendGetParam()))? appendGetParam('page').'&': '';?>page=<?php echo $totalPageNum ?>">></a></li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </section>
            </div>
        </main>
        <?php require('footer.php') ?>
    </body>
</html>
