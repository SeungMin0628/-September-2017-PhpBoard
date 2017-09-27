<?php
    /**
     * Created by PhpStorm.
     * Title    : Control_ReplyManager
     * Content  : define class to manage reply data
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-23 20:52
     */

    // 01. include php file
    include_once 'Control_SessionManager.php';
    include_once 'Control_BoardWriter.php';
    include_once 'Control_BoardManager.php';
    include_once '../model/Model_Reply.php';
    include_once '../model/Model_SelectDAO.php';
    include_once '../model/Model_UpdateDAO.php';
    include_once '../model/Model_DeleteDAO.php';
    include_once '../model/Model_insertDAO.php';

    // 02. define class
    class ReplyManager {
        // 01) set variable
        private $boardId;

        // 02) define constructor
        function __construct($argBoardId) {
            $this->boardId = $argBoardId;
        }

        // 03) insert reply
        function insertReply($argContent, $argReplyPid) {
            // 3-01) set variable
            $sm         = new SessionManager();
            $bw         = new BoardWriter(null, null);
            $insertDao  = new InsertDAO();
            $writer     = $sm->getUserInfo()['id'];
            $content    = $bw->encodeHTML($argContent);

            // 3-02) get upload time
            $dateObj    = new DateTime();
            $dateObj->setTimestamp($_SERVER['REQUEST_TIME']);
            $timeStr    = $dateObj->format('Y-m-d H:i:s');

            // 3-03) make reply object
            $reply      = new Reply($argReplyPid, $writer, $content, $timeStr, $this->boardId);

            // 3-04) input reply object on database
            return $insertDao->insertReply($reply);
        }

        // 02. select reply list
        function readReplyList() {
            // 01) set variable
            $selectDao = new SelectDAO();

            // 02) return reply list
            return $selectDao->selectReplyList($this->boardId);
        }

        // 03. remove reply
        function removeReply($argReplyId) {
            // 01) set variable
            $deleteDAO = new DeleteDAO();

            // 02) return flag
            return $deleteDAO->deleteReply($argReplyId);
        }

        // 04. update reply
        function updateReply($argReplyId, $argContent) {
            // 01) set variable
            $updateDAO = new UpdateDAO();
            $bw = new BoardWriter(null, null);
            $content = $bw->encodeHTML($argContent);

            // 02) create reply object
            $replyObj = new Reply(null, null, $content, null, null);
            $replyObj->setId($argReplyId);

            return $updateDAO->updateReply($replyObj);
        }

        // 05.
        function actFunction($argType) {
            switch($argType) {
                case 'send':
                    $content = isset($_POST['content']) ? $_POST['content'] : null;
                    $replyPid   = isset($_POST['pid']) ? $_POST['pid'] : 0;
                    if($this->insertReply($content, $replyPid)) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;
                case 'read':
                    if($result = $this->readReplyList()) {
                        $bm = new BoardManager;
                        foreach($result['replyList'] as $key=>$value) {
                            $result['replyList'][$key]['content'] = $bm->decodeHTML($value['content']);
                        }
                        echo json_encode($result);
                    } else {
                        echo 'false';
                    }
                    break;
                case 'update_reply':
                    $replyId = isset($_POST['reply_id']) ? $_POST['reply_id'] : null;
                    $content = isset($_POST['content']) ? $_POST['content'] : null;
                    if($this->updateReply($replyId, $content)) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;
                case 'delete_reply':
                    $replyId = isset($_POST['reply_id']) ? $_POST['reply_id'] : null;
                    if($this->removeReply($replyId)) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;

            }
        }
    }


    // 03. create object and act
    $type       = isset($_POST['type']) ? $_POST['type'] : null;
    $boardId    = isset($_POST['board_id']) ? $_POST['board_id'] : null;

    $replyManager = new ReplyManager($boardId);
    echo $replyManager->actFunction($type);
?>