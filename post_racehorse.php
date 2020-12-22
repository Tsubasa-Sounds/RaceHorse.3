<?php
    require_once 'daos/RacehorseDAO.php';
    session_start();
    
    if($_SESSION['user_id'] === null){
        $_SESSION['error_message'] = '不正アクセスです';
        header('Location: index.php');
        exit;
    }else{
        $user_id = $_SESSION['user_id'];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $racehorse_name = $_POST['racehorse_name'];
            $jockey_name = $_POST['jockey_name'];
            $racecourse = $_POST['racecourse'];
            $race_name = $_POST['race_name'];
            $content = $_POST['content'];
            $racehorse_dao = new RacehorseDAO();
            $image = $racehorse_dao->upload();
            $racehorse = new Racehorse($user_id, $image, $racehorse_name, $jockey_name, $racecourse, $race_name, $content);
            $racehorse_dao->insert($racehorse);
            $racehorse_dao = null;
            $_SESSION['flash_message'] = '投稿しました';
            header('Location: mypage.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>新規レース投稿</title>
    </head>
    <body>
        <h1>新規レース投稿</h1>
        <form action="post_racehorse.php" method="POST" enctype="multipart/form-data">
            画像<input type="file" name="image"><br>
            競走馬の名前<input type="text" name="racehorse_name"/><br>
            ジョッキー名<input type="text" name="jockey_name"/><br>
            レースコース<input type="text" name="racecourse"/><br>
            レース名<input type="text" name="race_name"/><br>
            コメント<input type="text" name="content"/><br>
            <input type="submit" value="レース投稿"/>
        </form>
        <a href="mypage.php">マイページに戻る</a>
    </body>
</html>