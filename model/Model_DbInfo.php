<?php
/**
 * Created by PhpStorm.
 * Title    : Model_DbInfo
 * Content  : define class to access database
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-20 22:09
 */

    class DBInfo {
        /* 1. set variable -> BD info */
        private $id             = 'root';
        private $pw             = 'autoset';
        private $dbName         = 'seungmin';
        private $host           = '127.0.0.1';
        private $boardTable     = 'board';
        private $replyTable     = 'reply';
        private $userInfoTable  = 'user_info';

        /* 2. get DB Connection*/
        function getConnection() {
            // 1. create connection obj
            $con = new mysqli($this->host, $this->id, $this->pw, $this->dbName);

            // 2. check
            if($con->connect_errno) {
                return false;
            }

            return $con;
        }

        /**
         * @return string
         */
        public function getBoardTable()
        {
            return $this->boardTable;
        }

        /**
         * @return string
         */
        public function getReplyTable()
        {
            return $this->replyTable;
        }

        /**
         * @return string
         */
        public function getUserInfoTable()
        {
            return $this->userInfoTable;
        }
    }
?>