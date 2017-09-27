<?php
    /**
     * Created by PhpStorm.
     * Title    : Model_SelectDAO
     * Content  : define class to update board data
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-20 22:18
     */


    class UpdateDAO
    {
        // 1. update board
        function updateBoard(Board $argBoardObj) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_updateBoard_connect');
                }

                // 2) set SQL sentence
                $sql = "UPDATE {$dbInfo->getBoardTable()}
                        SET title = '{$argBoardObj->getTitle()}',
                        content = '{$argBoardObj->getContent()}'
                        WHERE board_id = {$argBoardObj->getId()}";

                // 3) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_updateBoard_query');
                }

                return true;
            } catch(Exception $e) {
                echo $e->getMessage();

                return false;
            } finally {
                $con->close();
            }
        }

        // 2. update Reply
        function updateReply($newReplyContent) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_updateReply_connect');
                }

                // 2) set SQL sentence
                $sql = "UPDATE {$dbInfo->getReplyTable()}
                        SET  content = '{$newReplyContent->getContent()}'
                        WHERE reply_id = {$newReplyContent->getId()}";

                // 3) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_updateReply_query');
                }

                return true;
            } catch(Exception $e) {
                echo $e->getMessage();

                return false;
            } finally {
                $con->close();
            }
        }

        // 3. include views
        function includeViews($boardId) {
            try {
                // 1) get connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if(!$con) {
                    throw new Exception('false_includeViews_connect');
                }

                // 2) set SQL sentence
                $sql = "UPDATE board
                        SET views = views + 1
                        WHERE board_id = {$boardId}";

                // 3) query SQL
                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_includeViews_query');
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