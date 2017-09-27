<?php
/**
 * Created by PhpStorm.
 * Title    : Model_SelectDAO
 * Content  : define class to select board data at database
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-14 11:20
 */
    /*
     *      ## if fail to connect database, must output message 'false'.
     */

    // include database info class
    include_once 'Model_DbInfo.php';
    include_once 'Model_UserInfo.php';

    /* SELECT DAO */
    class SelectDAO {
        private $pagePerList, $queryFilter;

        function __construct() {
            $this->pagePerList = 10;            // page per list
        }

        // 1. select board list (in search condition)
        function selectBoardList($pList, $sType, $sContent) {
            // 1) get Connection
            $dbInfo = new DBInfo();
            $con    = $dbInfo->getConnection();
            if(!$con) {
                echo 'false_selectBoard_connect';
                return;
            }

            // 2) set SQL sentence
            $sql = "SELECT board_id, title, user_name, views, DATE_FORMAT(write_time, '%m-%d %H:%i') AS upload_time  
                    FROM board b
                    JOIN user_info u
                    ON (writer = id)";

            // when receive search type and data
            $this->setQueryFilter($sType, $sContent);
            $sql .= $this->queryFilter;

            // get page list
            $nextPage = $pList * ($this->pagePerList);
            $sql .= "ORDER BY board_id DESC LIMIT {$nextPage}, {$this->pagePerList}";


            // 3) act SQL
            if(!($sql_result = $con->query($sql))) {
                echo 'false_selectBoard_query';
                return;
            }

            if(!$sql_result) {
                echo 'false_selectBoard_noSelect';
                return;
            }

            // 4) get data of sql result
            $result_array = array();
            while($row = $sql_result->fetch_assoc()) {
                array_push($result_array, $row);
            }

            // 5) close connection
            $con->close();

            return $result_array;
        }

        // 2. select reply list
        function selectReplyList($boardNum) {
            try {
                // 01) get connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();

                // 02) set variable
                $result     = array();
                $replyList  = array();

                // 03) get reply list
                // 03-1) set SQL
                $sql    = "SELECT r.reply_id, r.reply_pid, u.user_name, r.content, DATE_FORMAT(r.write_time, '%Y-%m-%d %H:%i:%s') AS 'write_time', r.board_id
                           FROM {$dbInfo->getReplyTable()} r
                           JOIN {$dbInfo->getUserInfoTable()} u
                           ON(r.writer = u.id)
                           WHERE r.board_id = {$boardNum}";

                // 03-2) act SQL query
                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_selectReplyList_firstQuery');
                }

                // 03-3) make reply object at query result
                while($row = $queryResult->fetch_assoc()) {
                    $replyObj = new Reply($row['reply_pid'], $row['user_name'], $row['content'],
                                    $row['write_time'], $row['board_id']);
                    $replyObj->setId($row['reply_id']);
                    $replyObj->setModifyRight($this->selectModifyRight(true, $row['reply_id']));

                    array_push($replyList, $replyObj->toArray());
                }

                // 04) get number of replies
                // 04-1) set sql
                $sql = "SELECT COUNT(*)
                        FROM reply 
                        WHERE board_id = {$boardNum}";

                // 04-2) act SQL query
                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_selectReplyList_secondQuery');
                }

                // 04-3) get query result
                $numOfReply = $queryResult->fetch_array()[0];

                // 05) set query result on result array
                $result['replyList']    = $replyList;
                $result['numOfReply']   = $numOfReply;

                return $result;
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            } finally {
                $con->close();
            }
        }

        // 3. select user info
        function selectUserInfo($userId) {
            // 1) get Connection
            $dbInfo = new DBInfo();
            $con    = $dbInfo->getConnection();
            if(!$con) {
                echo 'false_selectUserInfo_connect';
                return;
            }

            // 2) set SQL sentence
            $sql    = "SELECT u.id, u.pw, u.user_name, u.grade, g.g_name
                       FROM user_info u
                       JOIN grade_info g
                       ON (u.grade = g.id)
                       WHERE u.id = '{$userId}'";

            // 3) act SQL
            if(!($result = $con->query($sql))) {
                echo 'false_selectUserInfo_sqlQuery';
                return;
            }

            if(!$result) {
                echo 'false_selectUserInfo_noSelect';
                return;
            }
            $con->close();

            // 4) create user info object
            $infoAry = $result->fetch_assoc();
            $userInfoObj = new UserInfo($infoAry['id'], $infoAry['pw'], $infoAry['user_name'],
                                        $infoAry['grade'], $infoAry['g_name']);

            return $userInfoObj;
        }

        // 4.  select list of pagination
        function selectMaxPage() {
            // 1) get Connection
            $dbInfo = new DBInfo();
            $con    = $dbInfo->getConnection();
            if(!$con) {
                echo 'false_selectPageList_connect';
                return;
            }

            // 2) set SQL sentence
            $sql    =  "SELECT CEIL(COUNT(*) / 10) 
                        FROM board b
                        JOIN user_info u
                        ON (writer = id)";
            $sql    .= $this->queryFilter;

            // 3) act SQL
            if(!($result = $con->query($sql))) {
                echo 'false_selectPageList_sqlQuery';
                return;
            }

            if(!$result) {
                echo 'false_selectPageList_noSelect';
                return;
            }
            $con->close();

            return $result->fetch_array()[0];
        }

        // 5. select board id of next board and prev board
        function selectNextPrevBoard($boardId) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if(!$con) {
                    throw new Exception('false_selectNextPrevBoardId_connect');
                }

                // 2) set SQL sentence
                $sql = "SELECT  board_id, title
                        FROM    {$dbInfo->getBoardTable()}
                        WHERE   board_id = (
                                            SELECT MAX(board_id)
                                            FROM board
                                            WHERE board_id < {$boardId})
                        OR      board_id = (
                                            SELECT MIN(board_id)
                                            FROM board
                                            WHERE board_id > {$boardId})";

                // 3) query SQL
                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_selectNextPrevBoardId_query');
                }


                // 4) create result array and push values
                $resultData = array();
                $resultData['next'] = null;
                $resultData['prev'] = null;

                while($row = $queryResult->fetch_assoc()) {
                    $resultData['next'] = $row['board_id'] > $boardId ?
                                            $row : $resultData['next'];
                    $resultData['prev'] = $row['board_id'] < $boardId ?
                                            $row : $resultData['prev'];
                }

                return $resultData;
            } catch(Exception $e) {
                echo $e->getMessage();
            } finally {
                $con->close();
            }
        }

        // 6. select board
        function selectBoard($boardId) {
            try {
                // 1) create connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if (!$con) {
                    throw new Exception('false_selectBoard_connect');
                }

                // 2) set SQL sentence
                $sql = "SELECT title, content, user_name, views, write_time
                        FROM {$dbInfo->getBoardTable()} b
                        JOIN {$dbInfo->getUserInfoTable()} u
                        ON (b.writer = u.id)
                        WHERE board_id = {$boardId}";

                // 3) query SQL
                if(!($result = $con->query($sql))) {
                    throw new Exception('false_selectBoard_query');
                }

                if(!($row = $result->fetch_assoc())) {
                    throw new Exception('false_selectBoard_noSelect');
                }

                // 4) make object at the query result
                $boardObj = new Board($row['title'], $row['content'], $row['user_name'], $row['write_time']);
                $boardObj->setViews($row['views']);

                return $boardObj;
            } catch(Exception $e) {
                echo $e->getMessage();

                return false;
            } finally {
                $con->close();
            }
        }

        // 7. check the board modify right
        function selectModifyRight($isReply, $boardId) {
            try {
                // 1) get connection
                $dbInfo = new DBInfo();
                $con    = $dbInfo->getConnection();
                if(!$con) {
                    throw new Exception("false_selectModifyRight_connect");
                }

                // 2) set variable
                include_once '../control/Control_SessionManager.php';
                $sm = new SessionManager();
                $userId = $sm->getUserInfo()['id'];

                // 3) check user's board manage right
                // 3-1) set SQL
                $sql = "SELECT g.manageable
                        FROM grade_info g
                        JOIN user_info u
                        ON (u.grade = g.id)
                        WHERE u.id = '{$userId}'";

                // 3-2) act sql
                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_selectModifyRight_firstQuery');
                }

                if(!($row = $queryResult->fetch_assoc())) {
                    throw new Exception('false_selectModifyRight_firstNoResult');
                }

                if($row['manageable']) {
                    return true;
                }

                // 4) when user doesn't have board manage right, check board's writer
                // 4-1) is it reply? or board?
                if($isReply) {
                    $tableName  = $dbInfo->getReplyTable();
                    $pkName     = 'reply_id';
                } else {
                    $tableName  = $dbInfo->getBoardTable();
                    $pkName     = 'board_id';
                }

                // 4-2) set SQL
                $sql = "SELECT CASE writer WHEN '{$userId}' THEN true ELSE false END AS manageable
                        FROM {$tableName}
                        WHERE {$pkName} = '{$boardId}'";

                if(!($queryResult = $con->query($sql))) {
                    throw new Exception('false_selectModifyRight_secondQuery');
                }

                if(!($row = $queryResult->fetch_assoc())) {
                    throw new Exception('false_selectModifyRight_secondNoResult');
                }

                if($row['manageable']) {
                    return true;
                } else {
                    return false;
                }
            } catch(Exception $e) {
                echo $e->getMessage();
                return false;
            } finally {
                $con->close();
            }
        }

        // 8. set SQL query filter sentence when search mode is on
        function setQueryFilter($sType, $sContent) {
            // when receive search type and data
            if(!(is_null($sType) || is_null($sContent))) {
                // when search type is all
                if($sType == 'all') {
                    $this->queryFilter = "WHERE title LIKE '%{$sContent}%'
                                          OR content LIKE '%{$sContent}%'
                                          OR user_name LIKE '%{$sContent}%'";
                } else if($sType == 'writer') {
                    $this->queryFilter = "WHERE writer LIKE '%{$sContent}%'
                                          OR user_name LIKE '%{$sContent}%'";
                } else {
                    $this->queryFilter = "WHERE {$sType} LIKE '%{$sContent}%'";
                }
            } else {
                $this->queryFilter = '';
            }
        }
    }
?>