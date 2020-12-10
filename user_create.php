<?php
    require_once 'daos/UserDAO.php';
    
    //var_dump($_POST);
    //var_dump($_FILES);
    $name = $_POST['name'];
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user_dao = new UserDAO();
    $avatar = $user_dao->upload();
    
    $user = new User($name, $nickname, $email, $avatar, $password);
    
    $user_dao->insert($user);
    
    include_once 'login.php';