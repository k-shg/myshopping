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
$message = $_SESSION['message'];
$_SESSION['message'] = '';

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
    $_SESSION['message'] = 'メッセージを送信しました';
    header("Location: {$_SERVER['PHP_SELF']}?order_id=$order_id");
}


$title = '連絡掲示板';
require('head.php') ?>
    <body>
        <style media="screen">

        .board-container {
            /* min-height: 600px; */
            /* background: red; */
        }

        /*---------------------------------------
         	取引情報
        --------------------------------------*/


        .msg-info .title {
            text-align: center;
            color: black;
        }

        .msg-info {
            padding: 15px;
            background: #f6f5f4;
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            border: 3px solid #bababa;
        }

        .partner-info {
            margin-left: 15px;
            display: flex;
            flex-direction: row;
        }
        .partner-info__img {
            width: 100px;
            height: 100px;
            margin-left: 50px;
            object-fit: cover;
        }
        .partner-info__profile div  {
            margin-bottom: 20px;
        }


        .product-info {
            display: flex;
            flex-direction: row;
        }

        .product-info__main{
        }
        .product-info__sub{
            margin-left: 50px;
        }
        .product-info__sub div {
            margin-bottom: 20px;
        }
        .product-info__img {
            width: 100px;
            object-fit: cover;
            height: 100px;
        }




        /*---------------------------------------
         	メッセージやりとり
        --------------------------------------*/

        .area-board {
        	min-height: 500px;
        	background: #ffffff;
        	width: 80%;
        	margin: 0 auto;
            overflow: hidden;
        }
        .area-board__container {
            width: 100%;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .area-board__msg {
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .area-board__msg--right{
            float: right;
        }
        .area-board__msg--left{
            float: left;
        }

        .area-board__avater--right{
            float: right;
            margin-left: 10px;
        }
        .area-board__avater--left{
            float: left;
            margin-right: 10px;
        }
        .area-board__img {
            width: 50px;
            height: 50px;
            border-radius: 20px;
            object-fit: cover;
        }

        .area-board__inr-txt {
            padding: 15px;
            width: 40%;
            border-radius: 10px;
            position: relative;
        }

        .area-board__inr-txt--left{
            background: #f6e2df;
            padding-left: 10px;
            float: left;
            margin-right: 50px;
        }
        .area-board__inr-txt--right{
            background: #d2eaf0;
            padding-right: 10px;
            float: right;
        }

        .triangle {
            position: absolute;
            top: 3px;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
        }
        .triangle--left {
            border-right: 20px solid #f6e2df;
            left: -10px;
        }

        .triangle--right {
            border-left: 20px solid #d2eaf0;
            right: -10px;
        }



        .area-board__date {
            font-size: 10px;
            height: 100%;
        }

        .area-board__date--right {
            text-align: right;
        }
        .area-board__date--left {
            text-align: left;
        }


        .area-board__avater {
            float: left;
        }
        .avater__img {
            width: 80px;
            height: 80px;
            border-radius: 40px;
            margin-left: 50px;
        }




        /*---------------------------------------
            メッセージ送信
        --------------------------------------*/

        .area-send-msg {
        	min-height: 200px;
        	float: left;
        	width: 100%;
        	margin-top: 20px;
        }


        .area-send-msg__text-container {
            overflow: hidden;
        }


        .area-send-msg__textarea {
            background: #f6f5f4;
            border: 3px solid #bababa;
            background: #f6f5f4;
            height: 120px;
            margin: 0 auto;
            margin-bottom: 10px;
        }

        .area-send-msg__btm {
            color: #fff;
            background: #0e3056;
            margin: 0 auto;
            width: 20%;
        }
        .area-send-msg__btm:hover {
            background: #ff5722;
        }

        </style>
        <div class="flash-msg js-flash-msg">
            <?php if(!empty($message)) echo $message; ?>
        </div>
        <?php require('header.php') ?>
            <main id="contents">
                <h1 class="site-title">連絡掲示板</h1>
                <div class="board-container site-width">
                    <div class="msg-info">
                        <div class="partner-info">
                            <div class="partner-info__profile">
                                <div>名前：<?php  echo $partner_data['name'];?></div>
                                <div>年齢： <?php echo $partner_data['age']; ?></div>
                                <div>email：<?php echo $partner_data['email'];?></div>
                            </div>

                             <div class="partner-info__avater">
                                 <img src="<?php echo $partner_data['pic'] ?>" alt="" class="partner-info__img">
                             </div>
                        </div>
                        <div class="product-info">
                            <div class="product-info__main">
                                <img class="product-info__img" src="<?php echo showImg($product_data['pic']) ?>" alt="">
                            </div>
                            <div class="product-info__sub">
                                <div>「<?php echo $product_data['name'] ?>」</div>
                                <div>値段： <?php echo $product_data['price'] ?>円</div>
                                <div>購入日： <?php echo $order_date ?></div>
                            </div>
                        </div>
                    </div>
                        <?php if(!empty($order_msg_data[0]['message_id'])): ?>
                        <div class="area-board">
                            <?php foreach ($order_msg_data as $key => $value): ?>
                                <?php if($value['from_user'] == $my_user_id): ?>
                                <div class="area-board__msg area-board__msg--right">
                                    <div class="area-board__container">
                                        <div class="area-board__avater--right">
                                            <img class="area-board__img" src="<?php echo $my_data['pic']?>" alt="">
                                        </div>
                                        <p class="area-board__inr-txt area-board__inr-txt--right">
                                            <span class="triangle triangle--right"></span>
                                            <?php echo $value['msg'] ?>
                                        </p>
                                    </div>
                                    <div class="area-board__date area-board__date--right">
                                        <?php echo $value['create_date'] ?>
                                    </div>
                                </div>
                                <?php else:?>
                                <div class="area-board__msg area-board__msg--left">
                                    <div class="area-board__container">
                                        <div class="area-board__avater--left">
                                            <img class="area-board__img" src="<?php echo $partner_data['pic'] ?>" alt="">
                                        </div>
                                        <p class="area-board__inr-txt area-board__inr-txt--left">
                                            <span class="triangle triangle--left"></span>
                                            <?php echo $value['msg'] ?>
                                        </p>
                                    </div>

                                    <div class="area-board__date area-board__date--left">
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
                            <div class="area-send-msg__text-container">
                                <textarea class="area-send-msg__textarea js-get-count" name="msg" placeholder="送信メッセージを入力してください"></textarea>
                                <div class="count-countainer" style="float:right;">
                                    <span class="js-show-count">0</span>/255文字
                                </div>
                            </div>
                            <input type="submit" name="name" value="送信" class="btn btn-mid area-send-msg__btm">
                        </form>
                    </div>
                </div>
            </main>
        <?php require('footer.php') ?>

    </body>
</html>
