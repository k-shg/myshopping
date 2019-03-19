<?php

require('function.php');


debug('ログアウトします');

//セッション変数を削除する
session_destroy();


//ログインページに戻る
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}


 ?>
