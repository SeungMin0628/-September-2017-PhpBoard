<?php
    /**
     * Created by PhpStorm.
     * Title    : Control_BoardManager
     * Content  : define class that includes function to get and modify board
     * User     : 2-WDJ 1401213 Seungmin Lee
     * Date     : 2017-09-23 15:54
     */

    // 01. import external class
    include_once '../model/Model_Board.php';
    include_once '../model/Model_SelectDAO.php';
    include_once '../model/Model_UpdateDAO.php';
    include_once '../model/Model_DeleteDAO.php';

    // 02. define class
    class BoardManager {
        // 01) set variable

        // 02) define constructor
        function __construct() {}

        // 03) get Board info
        function getBoard($argBoardId) {
            // 3-01) set variable
            $result         = array();
            $selectDao      = new SelectDAO();
            $updateDao      = new UpdateDAO();

            // 3-02) get board obj
            $result['board']    = $selectDao->selectBoard($argBoardId)->toArray();

            // 3-03) put the board id of prev board and next board, in a array
            $result['number']   = $selectDao->selectNextPrevBoard($argBoardId);
            if(!is_null($result['number']['next'])) {
                $result['number']['next']['title'] =
                    $this->decodeHTML($result['number']['next']['title']);
            }

            if(!is_null($result['number']['prev'])) {
                $result['number']['prev']['title'] =
                    $this->decodeHTML($result['number']['prev']['title']);
            }

            // 3-04) update views of board
            $updateDao->includeViews($argBoardId);

            // 3-05) check the board modify right
            $result['modify']   = $selectDao->selectModifyRight(false, $argBoardId);

            // 3-06) decode text value
            $result['board']['title']   = $this->decodeHTML($result['board']['title']);
            $result['board']['content'] = $this->decodeHTML($result['board']['content']);

            // 3-07) return array
            return $result;
        }

        // 04) delete board
        function deleteBoard($board_id) {
            // 01) set variable
            $deleteDao = new DeleteDAO();

            // 02) delete board
            return $deleteDao->deleteBoard($board_id);
        }

        // 05) decode html entity
        function decodeHTML($textValue) {
            $decodeValue = html_entity_decode(str_replace('&nbsp;', ' ', $textValue), ENT_QUOTES);

            return $decodeValue;
        }

        // 06) receive type and act function
        function actFunction($type) {
            switch($type) {
                case 'view':
                    $board_id = isset($_POST['board_id']) ? $_POST['board_id'] : null;
                    echo json_encode($this->getBoard($board_id));
                    break;
                case 'delete':
                    $board_id = isset($_POST['board_id']) ? $_POST['board_id'] : null;
                    if($this->deleteBoard($board_id)) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                    break;
            }
        }
    }

    // 03. create object and act function
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    if(!is_null($type)) {
        $boardManger = new BoardManager();
        $boardManger->actFunction($type);
    } else {
        echo 'false';
    }
?>