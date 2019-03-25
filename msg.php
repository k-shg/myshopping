<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「連絡掲示板ページ「「「「「「「「「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

require('auth.php');


//注文情報とメッセージ情報を取得する
$order_id =  ($_GET) ? $_GET['order_id']: '';
$order_msg_data = getOederdAndMsg($order_id);


$buy_user_id = $order_msg_data[0]['buy_user'];
$sale_user_id = $order_msg_data[0]['sale_user'];

//自分のユーザーIDを取得
$my_user_id = $_SESSION['user_id'];
//取引相手のユーザーIDを取得する。(自分が購入者なら相手は販売者。)
$partner_id = ($buy_user_id == $my_user_id)? $sale_user_id: $buy_user_id;

//IDからユーザー詳細情報を取得
$my_data = getUser($my_user_id);
$partner_data = getUser($partner_id);
debug('取引相手の情報：'.print_r($partner_data, true));

//商品情報を取得
$product_id = $order_msg_data[0]['product_id'];
$product_data = getProduct($product_id);
//購入日を取得
$order_date =  $order_msg_data[0]['order_date'];
$order_date = date('Y-m-d', strtotime($order_date));

debug('GET情報：'.print_r($_GET, true));
debug('注文ID：'.$order_id);
debug('注文、メッセージ情報：'.print_r($order_msg_data, true));



if(!empty($_POST)) {
    debug('POST送信があります');
    debug('POST送信情報；'.print_r($_POST));
    $msg = $_POST['msg'];
    //メッセージ情報に値を格納
    sendMessage($order_id, $msg, $my_user_id, $partner_id);
    header("Location: {$_SERVER['PHP_SELF']}?order_id=$order_id");
}


$title = '連絡掲示板';
require('head.php') ?>
    <body>
        <style media="screen">
        .area-send-msg .btn-send {
            width: 150px;
            float: right;
            margin-top: 0;
            margin: 15px 0;
            padding: 15px 30px;
            border: none;
            background: #333;
            color: white;
            font-size: 14px;
        }

        .area-board .msg-cnt {
            width: 80%;
            height: 40px;
            background: yellow;
            margin-bottom: 30px;
        }
        .area-board .msg-cnt.msg-right{
            float: right;
        }
        .area-board .msg-cnt.msg-left{
            float: left;
        }
        .avater img {
            width: 40px;
            height: 40px;
        }
        .area-board .msg-cnt.msg-right .avater{
            float: right;
        }
        .area-board .msg-cnt.msg-left .avater{
            float: left;
        }
        .area-board .msg-cnt.msg-left .inr-txt{
            background: #f6e2df;
            padding-left: 10px;
        }
        .area-board .msg-cnt.msg-right .inr-txt{
            background: #d2eaf0;
            padding-right: 10px;
        }
        .inr-txt {
            padding: 10px;
        }
        .msg-info .title {
            text-align: center;
            color: black;
        }
        .avater-img img {
            width: 80px;
            height: 80px;
            border-radius: 40px;
            margin-left: 50px;
        }
        .msg-info {
            padding: 15px;
            background: #f6f5f4;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .avater-img {
            float: left;
        }
        .partner-info {
            float: left;
            margin-left: 15px;
            width: 500px;
        }
        .product-info {
            float: left;
            width: 300px;
        }
        .product-info .left{
            float: left;
        }
        .product-info .right{
            float: left;
            width: 200px;
        }
        .product-info img {
            width: 70px;
            height: 70px;
        }
        </style>
        <?php require('header.php') ?>
            <main id="contents">
                <h1 class="site-title">連絡掲示板</h1>
                <div class="board-container site-width">
                    <div class="msg-info">
                        <div class="avater-img">
                            <img src="<?php echo $partner_data['pic'] ?>" alt="" class="avater">
                        </div>
                        <div class="partner-info">
                            <?php
                            echo $partner_data['name'].'<br>';
                            echo $partner_data['age'].'<br>';
                            echo $partner_data['email'].'<br>';
                             ?>
                        </div>
                        <div class="product-info">
                            <div class="left">
                                取引商品<br>
                                <img src="<?php echo showImg($product_data['pic1']) ?>" alt="">
                            </div>
                            <div class="right">
                                「<?php echo $product_data['name'] ?>」<br>
                                値段: <?php echo $product_data['price'] ?>円<br>
                                購入日: <?php echo $order_date ?><br>
                            </div>
                        </div>
                    </div>
                        <?php if(!empty($order_msg_data[0]['message_id'])): ?>
                        <div class="area-board">
                            <?php foreach ($order_msg_data as $key => $value): ?>
                                <?php if($value['from_user'] == $my_user_id): ?>
                                <div class="msg-cnt <?php echo 'msg-right' ?>">
                                    <div class="avater">
                                        <img src="<?php echo $my_data['pic']?>" alt="">
                                    </div>
                                    <p class="inr-txt">
                                        <?php echo $value['msg'] ?>
                                    </p>
                                    <div style="text-align: right;">
                                        <?php echo $value['create_date'] ?>
                                    </div>
                                </div>
                                <?php else:?>
                                <div class="msg-cnt <?php echo 'msg-left'; ?>">
                                    <div class="avater">
                                        <img src="<?php echo $partner_data['pic'] ?>" alt="">
                                    </div>
                                    <p class="inr-txt">
                                        <?php echo $value['msg'] ?>
                                    </p>
                                    <div style="text-align: left">
                                        <?php echo $value['create_date'] ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php else://　上下中央揃えで表示させる ?>
                        <div class="area-board" style="line-height: 500px;">
                            <div style="text-align:center;">
                                投稿はまだありません
                            </div>

                        <?php endif; ?>
                    </div>
                    <div class="area-send-msg">
                        <form method="post">
                            <textarea name="msg" rows="12" cols="80" style="background: white;"></textarea>
                            <input type="submit" name="name" value="送信" class="btn btn-send">
                        </form>
                    </div>
                </div>
            </main>
        <?php require('footer.php') ?>

    </body>
</html>
