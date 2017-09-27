<?php
/**
 * Created by PhpStorm.
 * Title    : Control_Login_Algorithm
 * Content  : build login system by session
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-13 22:14
 */

    include_once '../model/Model_SelectDAO.php';
    include_once '../model/Model_UserInfo.php';

    /* 01. login algorithm */
    function login() {
        // 1) get variable and create DAO object
        $user_id        = $_POST['user_id'];
        $user_pw        = $_POST['user_pw'];

        // 2) connect DB
        $dbCon   = new SelectDAO();

        // 3) get user info to database
        $user_data = $dbCon->selectUserInfo($user_id);

        // 4) check password
        if($user_data->getPw() == $user_pw) {
            // 4-1) create Session Manager
            include './Control_SessionManager.php';
            $sm = new SessionManager();

            // 4-2) create session
            $sm->createSession($user_data);
        }
    }

    /* 03. logout algorithm*/
    function logout() {
        // 1) create session manager object
        include './Control_SessionManager.php';
        $sm = new SessionManager();

        // 2) remove session
        $sm->removeSession(false);
    }

    /* 04. select function */
    $type   = $_POST['type'];

    switch($type) {
        case 'check':
            include 'Control_SessionManager.php';
            $sm = new SessionManager();
            if($sm->checkLogin()) {
                echo json_encode($sm->getUserInfo(), true);
            } else {
                echo 'false';
            }
            break;
        case 'login':
            login();
            if(sizeof($_SESSION) > 0) {
                echo 'true';
            } else {
                echo 'false';
            }
            break;
        case 'logout':
            logout();
            break;
    }
?>