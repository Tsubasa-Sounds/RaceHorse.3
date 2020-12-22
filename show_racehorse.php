<?php
    require_once 'daos/RacehorseDAO.php';
    require_once 'daos/UserDAO.php';
    // セッションスタート
    session_start();
    
    $user_id = $_SESSION['user_id'];
        
    // UserDAOインスタンス生成
    $user_dao = new UserDAO();
    
    // user_dao の get_user_by_id() メソッドを使ってログインしている自分自身のインスタンスを生成
    $me = $user_dao->get_user_by_id($user_id);
    
    // user_dao の破棄
    $user_dao = null;
    
    //var_dump($_GET);
    $racehorse_id = $_GET['racehorse_id'];
    $racehorse_dao = new RacehorseDAO();
    $racehorse = $racehorse_dao->get_rasehorse_by_id($racehorse_id);
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
            <img src="<?php print USER_IMAGE_DIR . $me->avatar; ?>">
            <?php print $me->nickname; ?>さん
            
            <div class="row mt-3">
                <h3 class="offset-sm-2 col-sm-8 text-center">投稿詳細</h3>
            </div>
            <div class="row mt-5">
                <table class="offset-sm-1 col-sm-10 table table-bordered table-striped">
                    <tr>
                        <th>投稿者</th>
                        <th>投稿画像</th>
                        <th>競走馬の名前</th>
                        <th>ジョッキー名</th>
                        <th>レースコース</th>
                        <th>レース名</th>
                        <th>コメント</th>
                        <th>投稿日時</th>
                    </tr>
                    <tr>
                        <td>
                            <?php print $racehorse->get_user()->name; ?>
                            <span class="avatar">
                                <img src="<?php print USER_IMAGE_DIR . $racehorse->get_user()->avatar; ?>">
                            </span>
                        </td>
                        <td><img src = "<?php print RACEHORSE_DIR . $racehorse->image; ?>"></td>
                        <td><?php print $racehorse->racehorse_name; ?></td>
                        <td><?php print $racehorse->jockey_name; ?></td>
                        <td><?php print $racehorse->racecourse; ?></td>
                        <td><?php print $racehorse->race_name; ?></td>
                        <td><?php print $racehorse->content; ?></td>
                        <td><?php print $racehorse->created_at; ?></td>
                    </tr>
                </table>
            </div>
            
            <a href="logout.php" class="btn btn-primary">ログアウト</a>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS, then Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
    </body>
</html>
