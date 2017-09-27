<?php
    /**
     * Created by PhpStorm.
     * Title    : Control_BoardWriter
     * Content  : define class to write board
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-21 19:22
     */

    // 1. make object to access database
    include_once '../model/Model_InsertDAO.php';
    include_once '../model/Model_UpdateDAO.php';
    include_once '../model/Model_Board.php';
    include_once 'Control_SessionManager.php';

    // 2. define class to write board
    class BoardWriter {
        // 01) set variable
        private $title, $content;

        // 02) define constructor
        function __construct($argTitle, $argContent) {
            if($argTitle == '' || is_null($argTitle)) {
                $this->title = 'default title';
            } else {
                $this->title = $this->encodeHTML($argTitle);
            }

            $this->content = $this->encodeHTML($argContent);
        }

        // 03) write board
        function writeBoard() {
            // 3-1) set variable
            $insertDao      = new InsertDAO();
            $timeData       = new DateTime();
            $timeData->setTimestamp($_SERVER['REQUEST_TIME']);
            $timeToString   = $timeData->format('Y-m-d H:i:s');

            $sessionManager = new SessionManager();
            $writerManager  = $sessionManager->getUserInfo()['id'];

            // 3-2) create board object
            $boardObject    = new Board($this->title, $this->content, $writerManager, $timeToString);

            // 3-3) send board data to insertDAO
            return $insertDao->insertBoard($boardObject);
        }

        // 04) update board
        function updateBoard($argBoardId) {
            // 4-1) set variable
            $updateDao      = new UpdateDAO();

            // 4-2) create board object
            $boardObject    = new Board($this->title, $this->content, null, null);
            $boardObject->setId($argBoardId);

            // 4-3) send board data to updateDAO
            return $updateDao->updateBoard($boardObject);
        }

        // 05) encode html entity
        function encodeHTML($textValue) {
            $encodeValue = nl2br(str_replace(' ', '&nbsp;', htmlentities($textValue, ENT_QUOTES)));

            return $encodeValue;
        }

        // 06) act function at type
        function actFunction($type) {
            switch($type) {
                case 'write':
                    if ($this->writeBoard()) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;
                case 'update':
                    $boardId = isset($_POST['id']) ? $_POST['id'] : null;
                    if ($this->updateBoard($boardId)) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;
            }
        }
    }

    // 3. create object and send board info to server
    // receive
    $type       = isset($_POST['type']) ? $_POST['type'] : null;
    $title      = isset($_POST['title']) ? $_POST['title'] : null;
    $content    = isset($_POST['title']) ? $_POST['content'] : null;

    // create object and process algorithm
    $boardWriter = new BoardWriter($title, $content);
    $boardWriter->actFunction($type);
?>