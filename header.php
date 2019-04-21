<header class="header">
    <div class="site-width">
        <h1 class="header__title"><a class="header__link" href="index.php">MY SHOPPING</a></h1>
        <nav id="nav-top" class="nav">
            <ul class="nav__menu">
                <?php if(empty($_SESSION['user_id'])) :?>
                <li class="nav__menu-item"><a href="signup.php" class="nav__link btn btn-primary">ユーザー登録</a></li>
                <li class="nav__menu-item"><a href="login.php" class="nav__link btn btn-primary">ログイン</a></li>
                <?php else :?>
                    <li class="nav__menu-item"><a href="logout.php" class="nav__link btn btn-primary">ログアウト</a></li>
                    <li class="nav__menu-item"><a href="mypage.php" class="nav__link btn btn-primary">マイページ</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
