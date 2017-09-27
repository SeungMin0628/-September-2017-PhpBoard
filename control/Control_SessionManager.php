<?php
/**
 * Created by PhpStorm.
 * Title    : Control_SessionManager
 * Content  : object to manage session
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-14 20:07
 */

    // 01. include user info class
    include_once '../model/Model_UserInfo.php';

    class SessionManager {
        /* 01. check session : is login now? */
        function checkLogin() {
            if(isset($_SESSION['user_info'])) {
                return true;
            } else {
                return false;
            }
        }

        /* 02. send user info */
        function getUserInfo() {
            return $_SESSION['user_info'];
        }

        /* 02. create session and write user info to session */
        function createSession(UserInfo $user_data) {
            // 1) destroy session if session is
            $this->removeSession(true);

            // 2) delete password value
            $user_data->setPw('');

            // 3) write user data to session
            $_SESSION['user_info']  = $user_data->toArray();
        }

        /* 03. delete session */
        function removeSession($sessionStart) {
            // 1) destroy session if session is
            if(session_status() == PHP_SESSION_ACTIVE) {
                session_reset();
                session_destroy();
            }

            // 2) if sessionStart is true -> create session
            if($sessionStart) {
                session_start();
            }
        }
    }
?>