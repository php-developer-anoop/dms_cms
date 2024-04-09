<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class School extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_school_master";
    }
    function index() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "School Master";
        $data["title"] = "School List";
        $data['access'] = checkWriteMenus(getUri(2));
        adminview('view-school', $data);
    }
    function add_school() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "School Master";
        $data["title"] = !empty($id) ? "Edit School" : "Add School";
        $data['access'] = checkWriteMenus(getUri(2));
        $savedData = $this->c_model->getSingle($this->table, 'id,school_name,school_code,city,state,address,pincode,status', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['school_name'] = !empty($savedData['school_name']) ? $savedData['school_name'] : '';
        $data['school_code'] = !empty($savedData['school_code']) ? $savedData['school_code'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-school', $data);
    }
    public function save_school() {
        $post = $this->request->getVar();
        $id = !empty($post['id']) ? $post['id'] : '';
        $data = [];
        $data['school_code'] = trim($post['school_code']);
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && (empty($id) || $duplicate['id'] !== $id)) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Entry';
            echo json_encode($response);
            exit;
        }
        $data['school_name'] = ucwords(trim($post['school_name']));
        $data['status'] = 'Active';
        
        $act_type = '';
        $act_details = '';
        
        if (empty($id)) {
            $act_type = 'Added A School';
            $act_details = 'Name [' . $data['school_name'] . '], Code [' . $data['school_code'] . ']';
        } elseif (!empty($id)) {
            $check = $this->c_model->getSingle($this->table, 'school_name,school_code', ['id' => $id]);
            $act_type = 'Edited A School';
        
            if ($check['school_name'] != $data['school_name']) {
                $act_details .= 'From [' . $check['school_name'] . '] to [' . $data['school_name'] . ']<br>';
            }
        
            if ($check['school_code'] != $data['school_code']) {
                $act_details .= 'From [' . $check['school_code'] . '] to [' . $data['school_code'] . ']';
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
        $url = base_url(ADMINPATH . 'add-school') . '?id=' . $last_id;
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
            $where[" school_name LIKE '%" . $searchString . "%' OR school_code LIKE '%" . $searchString . "%'"] = null;
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
}
