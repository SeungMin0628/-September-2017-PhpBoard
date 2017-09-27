<?php
/**
 * Created by PhpStorm.
 * Title    : Control_ListManager
 * Content  : define class to get board list
 * User     : 2-WDJ 1401213 Seungmin Lee
 * Date     : 2017-09-18 10:23
 */

    // 1. create Manager Object
    include_once '../model/Model_SelectDAO.php';
    include_once './Control_SessionManager.php';
    $objDAO = new SelectDAO();
    $sesMag = new SessionManager();

    // 2. define class BoardManager
    class ListManager {
        // 01) set variable
        private $pageNum, $searchType, $searchContent;

        // 02) define construct
        function __construct($argPageNum, $argSearchType, $argSearchContent) {
            $this->pageNum = $argPageNum;

            if(!is_null($argSearchType) && !is_null($argSearchContent)) {
                $this->searchType = $argSearchType;
                $this->searchContent = $argSearchContent;
            }
        }

        // 03) get list of page
        function getPageList() {
            // 1) set variable
            global $objDAO, $sesMag;

            // 2) get board data
            $result['boardList'] = $objDAO->selectBoardList($this->pageNum,
                $this->searchType, $this->searchContent);

            // 3) get pagination data
            $result['maxList'] = $objDAO->selectMaxPage();

            // 4) check login
            $result['login'] = $sesMag->checkLogin();

            return json_encode($result, true);
        }
    }

    // 3. set variable
    $pNum       = $_POST['page_number'];
    $sType      = isset($_POST['search_type']) ? $_POST['search_type'] : null;
    $sContent   = isset($_POST['search_content']) ? $_POST['search_content'] : null;

    // 4. check received data and act function
    $listManager = new ListManager($pNum, $sType, $sContent);
    echo $listManager -> getPageList();
?>