<?php
// display_errorsをONに設定
ini_set('display_errors',1);

ini_set('log_errors', 1);
ini_set('error_log', 'php.log');
// 全てのエラーを表示
error_reporting(E_ALL);


// ===========================
//　セッション
//============================

debug('セッション開始');
debug('ーーーーーーーーーーーセッションチェックーーーーーーーーーーーーーーーー');
session_start();
session_regenerate_id();

debug('現在のセッションID：'.session_id());
debug('セッション情報：'.print_r($_SESSION, true));





// ===========================
//　グローバル変数定義
//============================
$error_msg = [];
$dbFormData = [];


// ===========================
//　エラーメッセージ用の定数
//============================
const MSG_EMPTY = '入力必須です';
const MSG_EMAIL = 'emailの形式で入力してください';
const MSG_OVER6 = '6文字以上で入力してください';
const MSG_UNDER255 = '255文字以下で入力してください';
const MSG_HALF_ALPHANUMERIC = '半角英数字で入力してください';
const MSG_RETYPE = 'パスワード(再入力)が一致しません';
const MGS_DB = 'データベースにエラーが発生しました。';
const MSG_LOGIN = ' メールアドレスまたはパスワードが違います';
const MSG_INT = '数値で入力してください';
const MSG_SELECTBOX = '選択値が間違っています';
const MSG_SELECTBOX_EMPTY = '選択必須です';


// ===========================
//　ログ出力デバッグ
//============================
function debug($str) {
    error_log('デバッグ：'.$str);
}

// ===========================
//　画面出力デバッグ
//============================

function dump($str) {
    echo('<pre>');
    var_dump($str);
    echo('</pre>');
}


// ===========================
//　バリデーション
//============================

function validRequired($str, $key) {
    global $error_msg;

    //入力チェック
    if(empty($str)) {
        $error_msg[$key] = MSG_EMPTY;
    }
    return $error_msg;
}

function validEmail($str, $key) {
    global $error_msg;

    if(!filter_var($str, FILTER_VALIDATE_EMAIL)) {
        $error_msg[$key] = MSG_EMAIL;
    }
}

function validLength($str, $key) {
    global $error_msg;

    if(strlen($str) < 6 ) {
        $error_msg[$key] = MSG_OVER6;
    } else if(strlen($str) > 255) {
        $error_msg[$key] = MSG_UNDER255;
    }
}

function validHalfAlpha($str, $key) {
    global $error_msg;

    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        $error_msg[$key] = MSG_HALF_ALPHANUMERIC;
    }
}

function validMatch($pass, $re_pass) {
    global $error_msg;
    if($pass !== $re_pass) {
        $error_msg['pass'] = MSG_RETYPE;
    }
}
function validMaxLen($str, $key) {
    global $error_msg;
    if(strlen($str) > 255) {
         $error_msg[$key] = MSG_UNDER255;
    }
}

function validNumber($num, $key) {
    global $error_msg;
    if(!is_int($num)) {
        $error_msg[$key] = MSG_INT;
    }
}

function validSelectBox($str, $key) {
    global $error_msg;

    //未選択の場合
    if($str == 0) {
        $error_msg[$key] = MSG_SELECTBOX_EMPTY;
    }else if(!preg_match("/^[0-9]+$/", $str)) {
        $error_msg[$key] = MSG_SELECTBOX;
    }
}


// ===========================
//　データベースへ接続
//============================

function dbConnect() {
    try {
        $dsn = 'mysql:dbname=myshopping;host=localhost;charset=utf8mb4';
        $username = 'root';
        $password = 'root';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        $dbh = new PDO($dsn, $username, $password, $options);
        return $dbh;
    } catch(Exception $e) {
        debug('データベース接続エラー：'.$e->getMessage());
    }
}

// ===========================
//　クエリー実行
//============================
function postQuery($dbh, $sql, $data) {
    //プリペアーステートメントを作成
    $stmt = $dbh->prepare($sql);
    //流し込んでDB実行
    $stmt->execute($data);
    return $stmt;
}



// ===========================
//　ユーザー取得
//============================
function getUser($user_id) {

    $dbh = dbConnect();
    $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg = 0';
    $data = [':user_id' => $user_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetch();
}

// ===========================
//　カテゴリー取得
//============================


function getCategory() {
    $result = '';

    try {
        debug('DB接続します');

        //DB接続
        $dbh = dbConnect();

        //クエリ発行
        $sql = 'SELECT id, name FROM category';
        $data = [];
        //クエリ実行
        $stmt = postQuery($dbh, $sql, $data);
        if($stmt) {
            debug('クエリ成功');
            $result = $stmt->fetchAll();
            //header('Location: mypage.php');
        } else {
            debug('クエリ失敗');
        }

    } catch (Exception $e) {
        error_log('例外発生：'.$e->getMessage());
        $error_msg['common'] = MGS_DB;
    }

    return $result;
}

// ===========================
//　商品データ取得
//============================

function getProduct($product_id) {

    $dbh = dbConnect();

    $sql = 'SELECT * FROM product WHERE id = :product_id AND delete_flg = 0';
    $data = [':product_id' => $product_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetch();
}

// ===========================
//　商品詳細データ取得
//============================

function getProductDetail($product_id) {

    $dbh = dbConnect();

    $sql = 'SELECT p.id, p.name, p.price, p.comment, p.pic, p.category_id, c.name as category_name, p.user_id FROM product as p INNER JOIN category as c on p.category_id = c.id WHERE p.id = :product_id AND p.delete_flg = 0';
    $data = [':product_id' => $product_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetch();
}



// ===========================
//　フォーム入力保持
//============================
function getFormData($str, $flg = false) {

    global $dbFormData;

    if($flg) {
        $method = $_GET;
    } else {
        $method = $_POST;
    }
    debug($str);

    //POSTされているとき
    if($method) {
        //データベースとPOST情報がちがうとき
        if($dbFormData[$str] !== $method[$str]) {
            debug(2);
            return $method[$str];
        }
        debug(3);
        return $dbFormData[$str];

    //データベースに情報があるとき
    } elseif(!empty($dbFormData[$str])) {
        debug(4);
        return $dbFormData[$str];
    } else {
        debug(5);
    }

}



// ===========================
//　画像フォーム入力保持
//============================
function getFormImageData($str) {

    global $dbFormData;
    global $error_msg;
    debug($str);

    //新しく画像が選択されているとき
    if(!empty($_FILES['pic']['name'])) {
        debug(1);
        //他の入力フォームにエラーがないとき
        if(empty($error_msg)){
            debug(2);
            return 'img/'.$_FILES['pic']['name'];
        }//他の入力フォームにエラーがあれば画像は未選択状態にする
        else {
            debug(3);
            return '';
        }


    //データベースに情報があるとき
    } elseif(!empty($dbFormData[$str])) {
        debug(4);
        return $dbFormData[$str];
    }  else if(!empty($error_msg)) {//画像は選択されているが、他の入力フォームでエラーが起きたとき
        debug(5);
        return 'img/'.$_FILES['pic']['name'];
    }
    else {
        debug(6);
        return 'img/'.$_FILES['pic']['name'];

    }
}




// ===========================
//　商品データを一覧で取得
//============================
function getProductList($offset_num, $category, $order) {
    $dbh = dbConnect();

    $sql = 'SELECT * FROM product WHERE ';
    $data = [];
    $result = [];

    //カテゴリーが選択されている場合
    if(!empty($category)) {
        $sql.= 'category_id = :category AND ';
        $data [':category'] = (int)$category;
    }

    $sql = $sql.'delete_flg = 0 AND purchase_flg = 0 ';

    //順序が選択されている場合
    if(!empty($order)) {

        switch($order) {
            case 1:
                $sql.= 'ORDER BY price DESC ';
                break;
            case 2:
                $sql.= 'ORDER BY price ASC ';
            }
    }

    $stmt = postQuery($dbh, $sql, $data);
    $result['total'] = $stmt->rowCount();


    //ページネーション用のSQL
    $sql .= 'LIMIT 20 OFFSET :offset_num ';

    $data['offset_num'] = $offset_num;

    $stmt = postQuery($dbh, $sql, $data);
    $result['data'] = $stmt->fetchAll();

    return $result;
}



// ===========================
//　商品購入
//============================

function buyProduct($product_id, $buy_user, $sale_user) {
    //DB接続
    $dbh = dbConnect();

    //クエリ発行
    $sql = 'INSERT INTO orders (product_id, buy_user, sale_user, create_date) VALUES (:product_id, :buy_user, :sale_user, :create_date)';
    $data = [
        ':product_id' => $product_id,
        ':buy_user' => $buy_user,
        ':sale_user' => $sale_user,
        ':create_date' => date('Y-m-d H:i:s')
        ];

    //クエリ実行
    $stmt = postQuery($dbh, $sql, $data);
    if($stmt) {
        debug('DB情報を更新しました');
        //header('Location: mypage.php');
    } else {
        debug('DB情報を更新できませんでした');
    }
    //商品の購入フラグを立てる
    changeProductFlg($product_id);
}

// ===========================
//　商品購入フラグ
//============================
function changeProductFlg($product_id) {
    //DB接続
    $dbh = dbConnect();

    $sql = 'UPDATE product SET purchase_flg = 1 WHERE id = :product_id';
    $data = [
        ':product_id' => $product_id,
        ];
    //クエリ実行
    $stmt = postQuery($dbh, $sql, $data);
    if($stmt) {
        debug('DB情報を更新しました');
    } else {
        debug('DB情報を更新できませんでした');
    }
}


// ===========================
//　出品した商品データを一覧で取得
//============================

function getMySellingProducts($user_id) {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM product WHERE user_id = :user_id AND delete_flg = 0';
    $data = [':user_id' => $user_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetchAll();
}

// ===========================
//　購入した商品データを一覧で取得
//============================
function getMyHavingProducts($user_id) {
    $dbh = dbConnect();
    $sql = 'SELECT p.id as product_id, p.name, p.price, p.pic, o.id as order_id, o.buy_user, o.sale_user FROM product as p INNER JOIN orders as o ON p.id = o.product_id WHERE o.buy_user = :buy_user AND p.delete_flg = 0';
    $data = [':buy_user' => $user_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetchAll();
}

// ===========================
//　メッセージを送信
//============================

function sendMessage($order_id, $msg, $from_user, $to_user) {
    //DB接続
    $dbh = dbConnect();

    //クエリ発行
    $sql = 'INSERT INTO message (order_id, msg, from_user, to_user, create_date) VALUES (:order_id, :msg, :from_user, :to_user, :create_date)';
    $data = [
        ':order_id' => $order_id,
        ':msg'=> $msg,
        ':from_user' => $from_user,
        ':to_user' => $to_user,
        ':create_date' => date('Y-m-d H:i:s')
        ];

    //クエリ実行
    $stmt = postQuery($dbh, $sql, $data);
    if($stmt) {
        debug('DB情報を更新しました');
        //header('Location: mypage.php');
    } else {
        debug('DB情報を更新できませんでした');
    }
}





// ===========================
//　注文に関するメッセージ情報をすべて取得
//============================
function getOederdAndMsg($order_id) {
    $dbh = dbConnect();
    $sql = 'SELECT o.id AS order_id, o.product_id AS product_id, o.buy_user, o.sale_user, o.create_date AS order_date, m.id AS message_id, m.msg, m.from_user, m.to_user, m.create_date AS create_date FROM orders AS o LEFT JOIN message AS m ON o.id = m.order_id AND m.delete_flg = 0 WHERE o.id = :order_id AND o.delete_flg = 0';
    $data = [':order_id' => $order_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetchAll();
}




// ===========================
//　自分に関わる注文データとメッセージ情報を一覧で取得
//============================
function getMyOrdersAndMsg($user_id) {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM orders WHERE buy_user = :user_id OR sale_user = :id AND delete_flg = 0';
    $data = [':user_id' => $user_id, ':id' => $user_id];
    $stmt = postQuery($dbh, $sql, $data);
    $orders = $stmt->fetchAll();
    //dump($orders);
    $result = [];
    foreach ($orders as $key => $value) {
        $result[$key] = $value;
        $sql = 'SELECT * FROM message WHERE order_id = :order_id AND delete_flg = 0 ORDER BY create_date DESC LIMIT 1';
        $data = [':order_id' => $value['id']];
        $stmt = postQuery($dbh, $sql, $data);
        $latest_msg = $stmt->fetch();
        $result[$key]['latest_msg'] = $latest_msg;
    }
    return $result;
}


// ===========================
//　メッセージデータを取得
//============================

function getBoardDetail($order_id) {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM message WHERE order_id = :order_id AND delete_flg = 0';
    $data = [':order_id' => $order_id];
    $stmt = postQuery($dbh, $sql, $data);
    return $stmt->fetch();
}



// ===========================
//　検索条件付きのリンクを生成
//============================
//appendGetParam関数を作ったことにより、使う必要性がなくなった
function getConditionLink($category, $order) {
    $link = '';
    if(!empty($category)) {
        $link .= "category_id=$category&";
    }
    if(!empty($order)) {
        $link .= "order=$order&";
    }
    if(empty($category) && empty($order)) {
        $link .= '';
    }
    return $link;
}


// ===========================
//　ページネーション
//============================
function pagination($currentPage, $totalPageNum, $pageColNum) {
        //1ページ目にいるとき。総ページ数が項目数以上の場合
    if($currentPage == 1 && $totalPageNum >= $pageColNum) {
        //右に5個カラムを表示させる
        $min_page = $currentPage;
        $max_page = $currentPage + 4;
        //2ページ目にいるとき
    }else if($currentPage == 2 && $totalPageNum >= $pageColNum) {
        $min_page = $currentPage - 1;
        $max_page = $currentPage + 3;
        //最終ページにいるとき
    }else if($currentPage == $totalPageNum && $totalPageNum >= $pageColNum) {
        $min_page = $currentPage - 4;
        $max_page = $totalPageNum;
        //最終の１つ前にいるとき
    }else if($currentPage == $totalPageNum - 1 && $totalPageNum >= $pageColNum) {
        $min_page = $currentPage - 3;
        $max_page = $totalPageNum;

        // 総ページ数が項目数より少ない場合。すべてのページを出す
    } else if($totalPageNum < $pageColNum) {
        $min_page = 1;
        $max_page = $totalPageNum;

    }else {
        //それ以外。3ページ目、4ページ目のとき
        //左右に２つづつ出す
        $min_page = $currentPage - 2;
        $max_page = $currentPage + 2;
    }
    $val['min'] = $min_page;
    $val['max'] = $max_page;

    return $val;
}

// ===========================
//　GETパラメーターを付与
//============================

//$delete_param  = 削除したいパラメータ
//GET情報がなければ空を返す
function appendGetParam($delete_param = null) {

    $param = "";

    if(!empty($_GET)) {
        if(!empty($delete_param)) unset($_GET[$delete_param]);
        foreach ($_GET as $key => $value) {
            $param .= "$key=$value&";
        }
        $param = substr($param, 0, -1);
    }
    return $param;
}


// ===========================
//　画像表示
//============================

function showImg($path) {

    if(!empty($path)) {
        return $path;
    } else {
        return 'img/Noimage_image.png';
    }
}

// ===========================
//　アップロードした画像のパスを取得
//============================

function getUploadingImgPath($file, $key) {
    debug('画像アップロード処理開始');
    debug('FILE情報：'.print_r($file, true));

    if(isset($file['error']) && is_int($file['error'])) {
        try{
            //バリデーション
            switch ($file['error']) {
                case UPLOAD_ERR_OK://ok
                    break;
                case UPLOAD_ERR_NO_FILE://未選択
                    throw new RunTimeException('ファイルが選択されていません');
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RunTimeException('ファイルサイズが大きすぎます');
                    break;
                default:
                    throw new RunTimeException('その他エラーが発生しました');
                    break;
            }

            $tmp_path = $file['tmp_name'];

            $type = @exif_imagetype($tmp_path);
            if(!in_array($type,[IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG])) {
                throw new RunTimeException('画像形式が非対応です');
            }

            $path = 'img/'.sha1_file($tmp_path).image_type_to_extension($type);

            if(!move_uploaded_file($tmp_path, $path)) {//ファイル移動
                throw new RunTimeException('ファイルアップロード時にエラーが発生しました');
            }
            chmod($path,  0644);//自分は読み書き。ブループ、他人は読み取りのみ
            debug('ファイルは正常にアップロードされました');
            debug('ファイルのpath：'.$path);
            return $path;

        }catch(RunTimeException $e) {
            debug($e->getMessage());
            global $error_msg;
            $error_msg[$key] = $e->getMessage();
        }
    }

}

 ?>
