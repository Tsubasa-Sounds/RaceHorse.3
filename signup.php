<?php
    // 外部ファイルの読み込み
    require_once 'daos/UserDAO.php';
    
    // セッション開始
    session_start();
    
    // 変数の初期化
    $flash_message = "";
    
    // POST通信ならば（= 新規投稿ボタンが押された時）
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // フォームからの入力値を取得
        $name = $_POST['name'];
        $nickname = $_POST['nickname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // 例外処理
        try {
            
            // データベースを扱う便利なインスタンス生成
            $user_dao = new UserDAO();
            // 画像ファイルの物理的アップロード処理
            $avatar = $user_dao->upload($_FILES);
            
            // 新しいuserインスタンスを生成
            $user = new User($name, $nickname, $email, $avatar, $password);

            // データベースにデータを1件保存
            $user_dao->signup($user);
            
            // 便利なインスタンス削除
            $user_dao = null;
                    
            // セッションにフラッシュメッセージを保存        
            $_SESSION['flash_message'] = "会員登録が成功しました。";
            header('Location: index.php');
            exit;

        } catch (PDOException $e) {
            echo 'PDO exception: ' . $e->getMessage();
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>新規会員登録</title>
    </head>
    <body>
        <h1>新規会員登録</h1>
        <form action="signup.php" method="post" enctype="multipart/form-data">
            お名前<input type="text" name="name"/><br>
            ニックネーム<input type="text" name="nickname"/><br>
            メールアドレス<input type="email" name="email"/><br>
            パスワード<input type="password" name="password"/><br>
            アバター画像<input type="file" name="avatar"/><br>
            <input type="submit" value="会員登録"/>
        </form>
    </body>
    
</html>