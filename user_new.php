<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>新規会員登録</title>
    </head>
    <body>
        <h1>新規会員登録</h1>
        <form action="user_create.php" method="post" enctype="multipart/form-data">
            お名前<input type="text" name="name"/><br>
            ニックネーム<input type="text" name="nickname"/><br>
            メールアドレス<input type="email" name="email"/><br>
            パスワード<input type="password" name="password"/><br>
            アバター画像<input type="file" name="avatar"/><br>
            <input type="submit" value="会員登録"/>
        </form>
    </body>
    
</html>