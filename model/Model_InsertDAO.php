<?php
    /**
     * Created by PhpStorm.
     * Title    : Model_SelectDAO
     * Content  : define class to insert board data at database
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-20 20:19
     */

    // 1. include db info
    include_once 'Model_DbInfo.php';

    // 2. define class of InsertDAO
    class InsertDAO
    {
        // 1. insert Board
        function insertBoard(Board $boardObj) {
            try {
                // 01) set variable and get database connect object
                $dbInfo = new DBinfo();
                $con =  $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_insertBoard_connect');
                }

                // 02) set SQL sentence
                $sql = "INSERT INTO {$dbInfo->getBoardTable()}(title, content, writer, write_time)
                        VALUES('{$boardObj->getTitle()}', '{$boardObj->getContent()}', '{$boardObj->getWriter()}',
                                STR_TO_DATE('{$boardObj->getUploadTime()}', '%Y-%m-%d %H:%i:%s'))";

                // 03) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_insertBoard_query');
                } else {
                    return true;
                }
            } catch (Exception $e) {
                /* process Exception */
                echo $e->getMessage();
                return false;
            }
        }

        // 2. insert Reply
        function insertReply(Reply $replyObj) {

            try {
                // 01) set variable and get database connect object
                $dbInfo = new DBinfo();
                $con =  $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_insertReply_connect');
                }

                // 02) set SQL sentence
                $sql = "INSERT INTO {$dbInfo->getReplyTable()}(reply_pid, writer, content, write_time, board_id)
                        VALUES({$replyObj->getPid()}, '{$replyObj->getWriter()}', '{$replyObj->getContent()}', 
                        STR_TO_DATE('{$replyObj->getWriteTime()}', '%Y-%m-%d %H:%i:%s'), {$replyObj->getBoardId()})";

                // 03) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_insertReply_query');
                } else {
                    return true;
                }
            } catch (Exception $e) {
                /* process Exception */
                echo $e->getMessage();
                return false;
            }
        }
    }
?>