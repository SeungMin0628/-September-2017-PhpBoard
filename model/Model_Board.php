<?php

    /**
     * Created by PhpStorm.
     * Title    : Model_Board
     * Content  : define class to input board info
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-20 20:19
     */

    class Board {
        // 1) set variable
        private $id, $title, $content, $writer, $views, $uploadTime;

        // 2) define constructor
        function __construct($argTitle, $argContent, $argWriter, $argUploadTime) {
            $this->setTitle($argTitle);
            $this->setContent($argContent);
            $this->setWriter($argWriter);
            $this->setUploadTime($argUploadTime);
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
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @param mixed $title
         */
        public function setTitle($title)
        {
            $this->title = $title;
        }

        /**
         * @return mixed
         */
        public function getContent()
        {
            return $this->content;
        }

        /**
         * @param mixed $content
         */
        public function setContent($content)
        {
            $this->content = $content;
        }

        /**
         * @return mixed
         */
        public function getWriter()
        {
            return $this->writer;
        }

        /**
         * @param mixed $writer
         */
        public function setWriter($writer)
        {
            $this->writer = $writer;
        }

        /**
         * @return mixed
         */
        public function getViews()
        {
            return $this->views;
        }

        /**
         * @param mixed $views
         */
        public function setViews($views)
        {
            $this->views = $views;
        }

        /**
         * @return mixed
         */
        public function getUploadTime()
        {
            return $this->uploadTime;
        }

        /**
         * @param mixed $uploadTime
         */
        public function setUploadTime($uploadTime)
        {
            $this->uploadTime = $uploadTime;
        }

        public function toArray() {
            $result = Array();

            $result['id']           = $this->getId();
            $result['title']        = $this->getTitle();
            $result['content']      = $this->getContent();
            $result['writer']       = $this->getWriter();
            $result['views']        = $this->getViews();
            $result['upload_time']  = $this->getUploadTime();

            return $result;
        }
    }

?>