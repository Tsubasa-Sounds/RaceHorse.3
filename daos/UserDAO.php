<?php
    //外部ファイルの読み込み
    require_once 'models/User.php';
    require_once 'config/Const.php';
    
    //データベースアクセスオブジェクト
    class UserDAO{
        
         // データベースと接続を行うメソッド
        public function get_connection(){
            $pdo = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            return $pdo;
        }
         // データベースとの切断を行うメソッド
        public function close_connection($pdo, $stmp){
            $pdo = null;
            $stmp = null;
        }
        // 全ユーザ情報を取得するメソッド
        public function get_all_users(){
            $pdo = $this->get_connection();
            $stmt = $pdo->query('SELECT * FROM users');
            // フェッチの結果を、Userクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            $users = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Userクラスのインスタンスの配列を返す
            return $users;
        }
        // id値からユーザデータを抜き出すメソッド
        public function get_user_by_id($id){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            $user = $stmt->fetch();
            $this->close_connection($pdo, $stmp);
            // ユーザクラスのインスタンスを返す
            return $user;
        }
        // 会員登録をするメソッド
        public function signup($user){
            $pdo = $this->get_connection();
            $stmt = $pdo -> prepare("INSERT INTO users (name, nickname, email, avatar, password) VALUES (:name, :nickname, :email, :avatar, :password)");
            // バインド処理
            $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
            $stmt->bindParam(':nickname', $user->nickname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
            $stmt->bindParam(':avatar', $user->avatar, PDO::PARAM_STR);
            $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
            $stmt->execute();
            $this->close_connection($pdo, $stmp);
        }
    
        // ログイン処理をするメソッド
        public function login($email, $password){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email=:email AND password=:password');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            $user = $stmt->fetch();
            $this->close_connection($pdo, $stmp);
            // userクラスのインスタンスを返す
            return $user;
    }
        // データを更新するメソッド
        public function update($user){
            $pdo = $this->get_connection();
            // ?
            $stmt = $pdo->prepare('UPDATE users SET name=:name, content=:content WHERE id = :id');
                            
            $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
            $stmt->bindParam(':content', $user->content, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);
            
            $stmt->execute();
            $this->close_connection($pdo, $stmp);
        }
        // データを削除するメソッド
        public function delete($id){
            $pdo = $this->get_connection();
            
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            $this->close_connection($pdo, $stmp);
    
        }
        // ファイルをアップロードするメソッド
        public function upload(){
            // ファイルを選択していれば
            if (!empty($_FILES['avatar']['name'])) {
                // ファイル名をユニーク化
                $image = uniqid(mt_rand(), true); 
                // アップロードされたファイルの拡張子を取得
                $image .= '.' . substr(strrchr($_FILES['avatar']['name'], '.'), 1);
                $file = USER_IMAGE_DIR . $image;
            
                // uploadディレクトリにファイル保存
                move_uploaded_file($_FILES['avatar']['tmp_name'], $file);
                
                return $image;
            }else{
                return null;
            }
        }
    }
?>