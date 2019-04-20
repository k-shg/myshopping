<header class="header">
    <div class="site-width">
        <h1 class="title"><a href="index.php">MY SHOPPING</a></h1>
        <nav id="nav-top">
            <ul class="menu">
                <?php if(empty($_SESSION['user_id'])) :?>
                <li class="menu-item"><a href="signup.php" class="btn btn-primary">ユーザー登録</a></li>
                <li class="menu-item"><a href="login.php">ログイン</a></li>
                <?php else :?>
                    <li class="menu-item"><a href="logout.php">ログアウト</a></li>
                    <li class="menu-item"><a href="mypage.php">マイページ</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
