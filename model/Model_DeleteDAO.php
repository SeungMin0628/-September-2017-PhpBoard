<?php
/**
 * Created by PhpStorm.
 * Title    : Model_DeleteDAO
 * Content  : define class to delete board data
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-20 22:17
 */
    class DeleteDAO
    {
        // 1. delete Board
        function deleteBoard($argBoardId) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_deleteBoard_connect');
                }

                // 2) set SQL sentence
                $sql = "DELETE FROM board 
                        WHERE board_id = {$argBoardId}";

                // 3) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_deleteBoard_query');
                }

                return true;
            } catch(Exception $e) {
                echo $e->getMessage();

                return false;
            } finally {
                $con->close();
            }
        }

        // 2. delete Reply
        function deleteReply($replyNum) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_deleteReply_connect');
                }

                // 2) set SQL sentence
                $sql = "DELETE FROM {$dbInfo->getReplyTable()} 
                        WHERE reply_id = {$replyNum}";

                // 3) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_deleteReply_query');
                }

                return true;
            } catch(Exception $e) {
                echo $e->getMessage();

                return false;
            } finally {
                $con->close();
            }
        }
    }
?>