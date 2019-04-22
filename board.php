<?php


require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「掲示板「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');


require('auth.php');

//ユーザーIDを取得
$user_id = $_SESSION['user_id'];

//出品した商品一覧を取得
$sale_product_list = getMySellingProducts($user_id);
debug('登録商品一覧を取得');

//購入した商品一覧を取得
$buy_product_list = getMyHavingProducts($user_id);
debug('購入商品一覧を取得');

//取引情報と最新メッセージを取得
$orders_and_msg = getMyOrdersAndMsg($user_id);
debug('最新メッセージ一覧を取得');
debug('取引情報：'.print_r($orders_and_msg, true));

//取引相手のユーザーIDリストを取得
$partner_id_list = [];
$my_user_id = $user_id;

foreach ($orders_and_msg as $key => $value) {
    $buy_user_id = $value['buy_user'];
    $sale_user_id = $value['sale_user'];
    //取引相手のユーザーIDを取得する。(自分が購入者なら相手は販売者。)
    $partner_id = ($buy_user_id == $my_user_id)? $sale_user_id: $buy_user_id;
    $partner_id_list[$key] = $partner_id;
}

//取引相手一覧を取得
$partner_name_list = [];
foreach ($partner_id_list as $key => $value) {
    $partner_data = getUser($value);
    $partner_name_list[$key] = $partner_data['name'];
}


$title = 'マイページ';
require('head.php') ?>


    <body>
        <?php require('header.php') ?>
        <main id="contents">
            <div class="main-container site-width">
                <h1 class="site-title">掲示板一覧</h1>
                <section id="main" class="products-container">
                    <div class="list list-table">
                        <table class="table">
                            <thead>
                                <tr class="table__tr">
                                    <th class="table__th">注文番号</th>
                                    <th class="table__th">取引相手</th>
                                    <th class="table__th">メッセージ</th>
                                    <th class="table__th">最新送信日時</th>
                                    <th class="table__th"></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders_and_msg as $key => $value) :?>
                                    <tr class="table__tr">
                                        <td class="table__td"><?php echo $value['id'] ?></td>
                                        <td class="table__td"><?php echo $partner_name_list[$key] ?></td>
                                        <td class="table__td">
                                            <a class="table__link" href="msg.php?order_id=<?php echo $value['id']?>">
                                                <?php echo (!empty($value['latest_msg']))? $value['latest_msg']['msg'] : 'まだメッセージはありません' ; ?>
                                            </a>
                                        </td>
                                        <td class="table__td"><?php echo (!empty($value['latest_msg']))? $value['latest_msg']['create_date'] : '---' ; ?></td>
                                        <td class="table__td">
                                            <a href="msg.php?order_id=<?php echo $value['id']?>">
                                                <i class="fas fa-link table__icon"></i>
                                            </a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
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
