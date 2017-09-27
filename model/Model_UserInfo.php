<?php
    /**
     * Created by PhpStorm.
     * Title    : Model_UserInfo
     * Content  : define class to user information
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-20 21:03
     */

    class UserInfo {
        // 01. set variable
        private $id, $pw, $userName, $grade, $gName;

        // 02. set constructor
        function __construct($argId, $argPw, $argUserName, $argGrade, $argGName) {
            $this->setId($argId);
            $this->setPw($argPw);
            $this->setUserName($argUserName);
            $this->setGrade($argGrade);
            $this->setGName($argGName);
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getPw()
        {
            return $this->pw;
        }

        /**
         * @param mixed $pw
         */
        public function setPw($pw)
        {
            $this->pw = $pw;
        }

        /**
         * @return mixed
         */
        public function getUserName()
        {
            return $this->userName;
        }

        /**
         * @param mixed $userName
         */
        public function setUserName($userName)
        {
            $this->userName = $userName;
        }

        /**
         * @return mixed
         */
        public function getGrade()
        {
            return $this->grade;
        }

        /**
         * @param mixed $grade
         */
        public function setGrade($grade)
        {
            $this->grade = $grade;
        }

        /**
         * @return mixed
         */
        public function getGName()
        {
            return $this->gName;
        }

        /**
         * @param mixed $gName
         */
        public function setGName($gName)
        {
            $this->gName = $gName;
        }

        public function toArray() {
            $result = Array();

            $result['id']       = $this->getId();
            $result['userName'] = $this->getUserName();
            $result['grade']    = $this->getGrade();
            $result['gName']    = $this->getGName();

            return $result;
        }
    }
?>