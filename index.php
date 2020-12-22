<?php
    session_start();
    $flash_message = '';
    $error_message = '';
    
    if($_SESSION['flash_message'] !== null){
        $flash_message = $_SESSION['flash_message'];
        $_SESSION['flash_message'] = null;
    }
    
    if($_SESSION['error_message'] !== null){
        $error_message = $_SESSION['error_message'];
        $_SESSION['error_message'] = null;
    }
    
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>RaceHorse Photo</title>
        <link rel="stylesheet" href="common/css/style.css">
        <style>
            .success{
                color: blue;
                background-color: skyblue;
            }
            .error{
                color: red;
                background-color: pink;
            }
        </style>
    </head>
    <body>
        <div>
            <h2 class="success"><?php print $flash_message; ?></h2>
            <h2 class="error"><?php print $error_message; ?></h2>
        </div>
        <header>
            <a href="index.php">
            <img id="logo" src="common/images/logo.jpg" alt="RaceHorse Photo">    
                <h1>RaceHorse Photo</h1>
            </a>
            <div class="" id="navber">
                <ul class="navber">
                    <li class="nav-item">
                        <a class="nav-login" href="login.php">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-signup" href="signup.php">新規登録</a>
                    </li>
                </ul>
            </div>
        </header>
        <main>
            <img id="mainphoto" src="common/images/main.jpeg" alt="RaceHorse Photo">
        </main>
        <footer>
            Copyright 2020 RaceHorse Photo. All Rights Reserved.
        </footer>
    </body>
</html>