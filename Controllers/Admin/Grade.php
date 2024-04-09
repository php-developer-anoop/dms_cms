<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Grade extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_grades";
    }
    function index() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Grade Master";
        $data["title"] = "Grade List";
        $data['access'] = checkWriteMenus(getUri(2));
        adminview('view-grade', $data);
    }
    function add_grade() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Grade Master";
        $data["title"] = !empty($id) ? "Edit Grade" : "Add Grade";
        $data['access'] = checkWriteMenus(getUri(2));
        $savedData = $this->c_model->getSingle($this->table, 'id,grade,status', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['grade'] = !empty($savedData['grade']) ? $savedData['grade'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-grade', $data);
    }
    public function save_grade() {
        $post = $this->request->getVar();
        $id = !empty($post['id']) ? $post['id'] : '';
        $data = [];
        $data['grade'] = trim($post['grade']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && (empty($id) || $duplicate['id'] !== $id)) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Entry';
            echo json_encode($response);
            exit;
        }
        $data['status'] = 'Active';
         $act_type = '';
        $act_details = '';
        
        if (empty($id)) {
            $act_type = 'Added A Grade';
            $act_details = $data['grade'];
        } else if (!empty($id)) {
            $check = $this->c_model->getSingle($this->table, 'grade', ['id' => $id]);
            $act_type = 'Edited A Grade';
        
            if ($check['grade'] != $data['grade']) {
                $act_details .= 'From [' . $check['grade'] . '] to [' . $data['grade'] . ']';
            }
        }
        
        if(!empty($act_details)){
            addUserLog($act_type, $act_details);
        }
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $message = 'Data Added Successfully';
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $last_id = $id;
            $message = 'Data Updated Successfully';
        }
        $url = base_url(ADMINPATH . 'add-grade') . '?id=' . $last_id;
        $response['status'] = true;
        $response['message'] = $message;
        $response['url'] = $url;
        echo json_encode($response);
        exit;
    }
    public function getRecords() {
        $post = $this->request->getVar();
        $get = $this->request->getVar();
        $limit = (int)(!empty($get["length"]) ? $get["length"] : 1);
        $start = (int)!empty($get["start"]) ? $get["start"] : 0;
        $is_count = !empty($post["is_count"]) ? $post["is_count"] : "";
        $totalRecords = !empty($get["recordstotal"]) ? $get["recordstotal"] : 0;
        $orderby = "DESC";
        $where = [];
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" grade LIKE '%" . $searchString . "%'"] = null;
            $limit = 100;
            $start = 0;
        }
        $countData = $this->c_model->countRecords($this->table, $where, 'id');
        if ($is_count == "yes") {
            echo (int)(!empty($countData) ? sizeof($countData) : 0);
            exit();
        }
        if (!empty($get["showRecords"])) {
            $limit = $get["showRecords"];
            $orderby = "DESC";
        }
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date';
        $listData = $this->c_model->getAllData($this->table, $select, $where, $limit, $start, $orderby);
        $result = [];
        if (!empty($listData)) {
            $i = $start + 1;
            foreach ($listData as $key => $value) {
                $push = [];
                $push = $value;
                $push["sr_no"] = $i;
                array_push($result, $push);
                $i++;
            }
        }
        $json_data = [];
        if (!empty($get["search"]["value"])) {
            $countItems = !empty($result) ? count($result) : 0;
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($countItems);
            $json_data["recordsFiltered"] = intval($countItems);
            $json_data["data"] = !empty($result) ? $result : [];
        } else {
            $json_data["draw"] = intval($get["draw"]);
            $json_data["recordsTotal"] = intval($totalRecords);
            $json_data["recordsFiltered"] = intval($totalRecords);
            $json_data["data"] = !empty($result) ? $result : [];
        }
        echo json_encode($json_data);
    }
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return $data;
    }
}
