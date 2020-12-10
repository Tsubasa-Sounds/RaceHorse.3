<?php
    // 外部ファイルの読み込み
    require_once 'daos/UserDAO.php';
    
    // セッション開始
    session_start();
    
    // 変数の初期化
    $flush_message = "";
    
    // POST通信ならば（= 新規投稿ボタンが押された時）
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // フォームからの入力値を取得
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // 例外処理
        try {
            
            // データベースを扱う便利なインスタンス生成
            $user_dao = new UserDAO();
            
            // ログイン処理
            $user = $user_dao->login($email, $password);
            
            // 便利なインスタンス削除
            $user_dao = null;
            
            //ユーザが存在すれば
            if($user){
                // セッションに、ユーザ番号とフラッシュメッセージを保存        
                $_SESSION['flash_message'] = "ログインしました。";
                $_SESSION['user_id'] = $user->id;
                //画面遷移
                header('Location: mypage.php');
                exit;
                
            }else{
                $flash_message = "入力内容が間違えています。";
            }

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
        <title>ログイン</title>
    </head>
    <body>
        <h1>ログイン</h1>
        <form action="login.php" method="POST">
            メールアドレス<input type="email" name="email"/><br>
            パスワード<input type="password" name="password"/><br>
            <input type="submit" value="ログイン"/>
        </form>
    </body>
    
</html>