<?php
    //外部ファイルの読み込み
    require_once 'models/Rasehorse.php';
    require_once 'models/User.php';
    require_once 'config/Const.php';
    //データベースアクセスオブジェクト
    class RacehorseDAO{
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
        // 全テーブル情報を取得するメソッド
        public function get_all_rasehorses(){
            $pdo = $this->get_connection();
            $stmt = $pdo->query('SELECT * FROM racehorses ORDER BY id DESC');
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
        }
        // id値からデータを抜き出すメソッド
            public function get_rasehorse_by_id($id){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorse = $stmt->fetch();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスを返す
            return $racehorse;
        }
        
        // データを1件登録するメソッド
        public function insert($racehorse){
            $pdo = $this->get_connection();
            $stmt = $pdo -> prepare("INSERT INTO racehorses (user_id, image, racehorse_name, jockey_name, racecourse, race_name, content) VALUES (:user_id, :image, :racehorse_name, :jockey_name, :racecourse, :race_name, :content)");
            // バインド処理
            $stmt->bindParam(':user_id', $racehorse->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':image', $racehorse->image, PDO::PARAM_STR);
            $stmt->bindParam(':racehorse_name', $racehorse->racehorse_name, PDO::PARAM_STR);
            $stmt->bindParam(':jockey_name', $racehorse->jockey_name, PDO::PARAM_STR);
            $stmt->bindParam(':racecourse', $racehorse->racecourse, PDO::PARAM_STR);
            $stmt->bindParam(':race_name', $racehorse->race_name, PDO::PARAM_STR);
            $stmt->bindParam(':content', $racehorse->content, PDO::PARAM_STR);
            $stmt->execute();
            $this->close_connection($pdo, $stmp);
        }
        // ファイルをアップロードするメソッド
        public function upload(){
            // ファイルを選択していれば
            if (!empty($_FILES['image']['name'])) {
                // ファイル名をユニーク化
                $image = uniqid(mt_rand(), true); 
                // アップロードされたファイルの拡張子を取得
                $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
                $file = RACEHORSE_DIR . $image;
            
                // uploadディレクトリにファイル保存
                move_uploaded_file($_FILES['image']['tmp_name'], $file);
                
                return $image;
            }else{
                return null;
            }
        }
        // 投稿idを指定して、投稿者のユーザ情報を取得するメソッド
        public function get_user($id){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM users JOIN racehorses ON users.id = racehorses.user_id WHERE racehorses.id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            $user = $stmt->fetch();
            $this->close_connection($pdo, $stmp);
            // Userクラスのインスタンスを返す
            return $user;
        }
        //キーワードで検索する
        public function find_racehorses($keyword){
            print 'OK';
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE racehorse_name LIKE :keyword OR jockey_name LIKE :keyword ORDER BY id DESC');
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
            $stmt->execute();
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
        }
        public function find_racehorses_by_racehorsename($keyword){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE racehorse_name =:keyword ORDER BY id DESC');
            $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->execute();
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
           
        }
        public function find_racehorses_by_jokeyname($keyword){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE jockey_name =:keyword ORDER BY id DESC');
            $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->execute();
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
        }
        public function find_racehorses_by_racecourse($keyword){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE racecourse =:keyword ORDER BY id DESC');
            $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->execute();
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
        }
        public function find_racehorses_by_racename($keyword){
            $pdo = $this->get_connection();
            $stmt = $pdo->prepare('SELECT * FROM racehorses WHERE race_name =:keyword ORDER BY id DESC');
            $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->execute();
            // フェッチの結果を、Racehorceクラスのインスタンスにマッピングする
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Racehorse');
            $racehorses = $stmt->fetchAll();
            $this->close_connection($pdo, $stmp);
            // Racehorseクラスのインスタンスの配列を返す
            return $racehorses;
        }
    }
?>