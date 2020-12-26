<?php
    // 外部ファイルの読み込み 
    require_once 'daos/UserDAO.php';
    require_once 'daos/RacehorseDAO.php';
    // セッションスタート
    session_start();
    
    $flash_message = '';
    
    if($_SESSION['user_id'] === null){
        $_SESSION['error_message'] = '不正アクセスです。';
        header('Location: index.php');
        exit;
    }else{

        // セッションからuser_idを抜き出す
        $user_id = $_SESSION['user_id'];
        
        // UserDAOインスタンス生成
        $user_dao = new UserDAO();
        
        // user_dao の get_user_by_id() メソッドを使ってログインしている自分自身のインスタンスを生成
        $me = $user_dao->get_user_by_id($user_id);
        
        // user_dao の破棄
        $user_dao = null;
        
        $racehorse_dao = new RacehorseDAO();
        //ポスト通信ならば
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //var_dump($_POST);
            $keyword = $_POST['keyword'];
            $kind = $_POST['kind'];
            //もし競走馬で検索する場合
            if($kind === '競走馬で検索'){
                $racehorses = $racehorse_dao->find_racehorses_by_racehorsename($keyword);
            }else if($kind === '騎手で検索'){
                $racehorses = $racehorse_dao->find_racehorses_by_jokeyname($keyword);
            }else if($kind === '競馬場で検索'){
                $racehorses = $racehorse_dao->find_racehorses_by_racecourse($keyword);
            }else if($kind === 'レースで検索'){
                $racehorses = $racehorse_dao->find_racehorses_by_racename($keyword);
            }else{
                 $racehorses = $racehorse_dao->find_racehorses($keyword);
            }
            $all_racehorses = $racehorse_dao->get_all_rasehorses();
        }else{  //ゲット通信ならば
             $all_racehorses = $racehorse_dao->get_all_rasehorses();
             $racehorses = $racehorse_dao->get_all_rasehorses();
        }
       
        $racehorse_dao = null;

        if($_SESSION['flash_message'] !== null && $_SESSION['flash_message'] !== '投稿しました'){
            $flash_message = 'ログインしました';
            $_SESSION['flash_message'] = null;
        }else if($_SESSION['flash_message'] !== null && $_SESSION['flash_message'] === '投稿しました'){
            $flash_message = $_SESSION['flash_message'];
            $_SESSION['flash_message'] = null;
        }
    }
 
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <link rel="stylesheet" href="common/css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <title>MyPage</title>
        <style>
            h2{
                color: blue;
                background-color: skyblue;
            }
            img{
                width: 15%;
            }
            table, tr, th, td{
                font-size: 10px;
            }
            td{
                width: 15%;
            }
            .avatar img{
                width: 50%;
            }
            span{
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <header>
            <a href="index.php">
            <img id="logo" src="common/images/logo.jpg" alt="RaceHorse Photo">    
                <h1>RaceHorse Photo</h1>
            </a>
        </header>
        <div class="container">
            <h2><? print $flash_message; ?></h2>
            <img class="floar-left" src="<?php print USER_IMAGE_DIR . $me->avatar; ?>">
            <?php print $me->nickname; ?>さん
            <a href="post_racehorse.php" class="btn btn-primary float-right">新規レース投稿</a>
            <div class="row">
                <form action="mypage.php" method="post">
                    <input type="text" name="keyword" placeholder="フリーキーワード">
                    <input type="submit" value="検索">
                </form>
            </div>
            <div class="row mt-3">
                <h3 class="offset-sm-2 col-sm-8 text-center">投稿一覧</h3>
            </div>
            <div class="row mt-5 clearfix">
                <table class="offset-sm-1 col-sm-10 table table-bordered table-striped">
                    <tr>
                        <th>
                            競走馬
                            <form action="mypage.php" method="POST">
                                <select name="keyword">
                                <?php foreach($all_racehorses as $racehorse){?>
                                <option value="<?php print $racehorse->racehorse_name; ?>">
                                    <?php print $racehorse->racehorse_name; ?>
                                </option>
                                <?php } ?>
                            </select>
                                <button type="submit" name="kind" value="競走馬で検索">検索</button>
                            </form>
                        </th>
                        <th>
                            騎手
                            <form action="mypage.php" method="POST">
                              <select name="keyword">
                                <?php foreach($all_racehorses as $racehorse){?>
                                <option value="<?php print $racehorse->jockey_name; ?>">
                                    <?php print $racehorse->jockey_name; ?>
                                </option>
                                <?php } ?>
                            </select>    
                                <button type="submit" name="kind" value="騎手で検索">検索</button>
                            </form>
                        </th>
                        <th>
                            競馬場
                             <form action="mypage.php" method="POST">
                            <select name="keyword">
                                <?php foreach($all_racehorses as $racehorse){?>
                                <option value="<?php print $racehorse->racecourse; ?>">
                                    <?php print $racehorse->racecourse; ?>
                                </option>
                                <?php } ?>
                            </select> 
                                <button type="submit" name="kind" value="競馬場で検索">検索</button>
                            </form>
                        </th>
                        <th>
                            レース名
                            <form action="mypage.php" method="POST">
                            <select name="keyword">
                                <?php foreach($all_racehorses as $racehorse){?>
                                <option value="<?php print $racehorse->race_name; ?>">
                                    <?php print $racehorse->race_name; ?>
                                </option>
                                <?php } ?>
                            </select>
                                <button type="submit" name="kind" value="レースで検索">検索</button>
                            </form>
                           
                        </th>
                        <th>お気に入り</th>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <?php foreach($racehorses as $racehorse){ ?>
                    　<a href="show_racehorse.php?racehorse_id=<?php print $racehorse->id; ?>"><img class="img-fluid" src = "<?php print RACEHORSE_DIR . $racehorse->image; ?>"></a>
                    <?php } ?>
                    <!--<table class="offset-sm-1 col-sm-10 table table-bordered table-striped">-->
                    <!--    <tr>-->
                    <!--        <th>投稿者</th>-->
                    <!--        <th>競走馬の名前</th>-->
                    <!--        <th>ジョッキー名</th>-->
                    <!--        <th>レースコース</th>-->
                    <!--        <th>レース名</th>-->
                    <!--        <th>コメント</th>-->
                    <!--        <th>投稿日時</th>-->
                    <!--    </tr>-->
                    <!--<?php foreach($racehorses as $racehorse){ ?>    -->
                    <!--    <tr>-->
                    <!--        <td>-->
                    <!--            <?php print $racehorse->get_user()->name; ?>-->
                    <!--            <span class="avatar">-->
                    <!--                <img src="<?php print USER_IMAGE_DIR . $racehorse->get_user()->avatar; ?>">-->
                    <!--            </span>-->
                    <!--        </td>-->
                    <!--        <td><?php print $racehorse->racehorse_name; ?></td>-->
                    <!--        <td><?php print $racehorse->jockey_name; ?></td>-->
                    <!--        <td><?php print $racehorse->racecourse; ?></td>-->
                    <!--        <td><?php print $racehorse->race_name; ?></td>-->
                    <!--        <td><?php print $racehorse->content; ?></td>-->
                    <!--        <td><?php print $racehorse->created_at; ?></td>-->
                    <!--    </tr>-->
                    <!--<?php } ?>    -->
                    <!--</table>-->
                    </div>
                </div>
            </div>
            <div class"row">
                <div class="col">
                    <a href="logout.php" class="btn btn-primary">ログアウト</a>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS, then Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
    </body>
</html>