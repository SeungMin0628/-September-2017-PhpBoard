<?php
    /**
     * Created by PhpStorm.
     * Title    : Model_Reply
     * Content  : define class to input reply info
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-20 20:23
     */

    // define class
    class Reply {
        // 01. set variable
        private $id, $pid, $writer, $content, $write_time, $board_id, $modifyRight;

        // 02. define constructor
        function __construct($argPid, $argWriter, $argContent, $argWriteTime, $argBoardId) {
            $this->setPid($argPid);
            $this->setWriter($argWriter);
            $this->setContent($argContent);
            $this->setWriteTime($argWriteTime);
            $this->setBoardId($argBoardId);
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
        public function getPid()
        {
            return $this->pid;
        }

        /**
         * @param mixed $pid
         */
        public function setPid($pid)
        {
            if(!is_null($pid)) {
                $this->pid = $pid;
            } else {
                $this->pid = null;
            }
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
        public function getWriteTime()
        {
            return $this->write_time;
        }

        /**
         * @param mixed $write_time
         */
        public function setWriteTime($write_time)
        {
            $this->write_time = $write_time;
        }

        /**
         * @return mixed
         */
        public function getBoardId()
        {
            return $this->board_id;
        }

        /**
         * @param mixed $board_id
         */
        public function setBoardId($board_id)
        {
            $this->board_id = $board_id;
        }

        /**
         * @return mixed
         */
        public function getModifyRight()
        {
            return $this->modifyRight;
        }

        /**
         * @param mixed $modifyRight
         */
        public function setModifyRight($modifyRight)
        {
            $this->modifyRight = $modifyRight;
        }

        public function toArray() {
            $result = array();

            $result['id'] = $this->getId();
            $result['pid'] = $this->getPid();
            $result['writer'] = $this->getwriter();
            $result['content'] = $this->getContent();
            $result['write_time'] = $this->getWriteTime();
            $result['board_id'] = $this->getBoardId();
            $result['modify'] = $this->getModifyRight();

            return $result;
        }
    }
?>