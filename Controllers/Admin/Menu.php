<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Menu extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_menus";
    }
    function index() {
        
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type']=$loginData['role'];
        $data["menu"] = "Menu Master";
        $data["title"] = "Menu List";
        $data['access']=checkWriteMenus(getUri(2));
        adminview('view-menu', $data);
    }
    function add_menu() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type']=$loginData['role'];
        // echo "<pre>";
        // print_r($data);exit;
        $data["menu"] = "Menu Master";
        $data["title"] = !empty($id) ? "Edit Menu" : "Add Menu";
        $data['access']=checkWriteMenus(getUri(2));
        
        $data["menu_list"] = $this->c_model->getAllData("menus", 'id,menu_title', ['status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['menu_title'] = !empty($savedData['menu_title']) ? $savedData['menu_title'] : '';
        $data['slug'] = !empty($savedData['slug']) ? $savedData['slug'] : '';
        $data['menu_type'] = !empty($savedData['menu_type']) ? $savedData['menu_type'] : '';
        $data['menu_id'] = !empty($savedData['menu_id']) ? $savedData['menu_id'] : '';
        $data['priority'] = !empty($savedData['priority']) ? $savedData['priority'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-menu', $data);
    }
    public function save_menu() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $data['menu_title'] = ucwords(trim($post['menu_title']));
        $data['slug'] = trim($post['menu_slug']);
        $data['menu_type'] = trim($post['menu_type']);
        $data['menu_id'] = trim($post['menu_id']);
       // $data['priority'] = trim($post['priority']);
        $data['status'] = 'Active';
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && (empty($id) || $duplicate['id'] !== $id)) {
            $this->session->setFlashdata('failed', 'Duplicate Entry');
            return redirect()->to(base_url(ADMINPATH . 'menu-list'));
        }
        $last_id = '';
        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            // $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $last_id = $id;
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'menu-list'));
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
            $where[" menu_title LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date';
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
    public function assign_menu() {
        $data = [];
        $uri = service('uri');
        $loginData = $this->session->get('login_data');
        $data['user_type']=$loginData['role'];
        $data['access']=checkWriteMenus(getUri(2));
        $data["title"] = "Assign Menu";
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
        if (!empty($id)) {
            $data['useremail']=getUserEmail($id);
            $data['user'] = $this->c_model->getSingle("users", 'read_menu_ids,write_menu_ids', ['id' => $id,'status' => 'Active','user_type'=>'Admin']);
        } else {
            $data['useremail']='';
            $data['user'] = [];
        }
        $data['id'] = $id;
        $data["menu_data"] = $this->c_model->getAllData("menus", "*,DATE_FORMAT(add_date,'%d-%m-%Y %r')", ['status' => 'Active','slug != '=>'user-activity-log'],null,null,'DESC','priority');
        $where=[];
        $where['status']='Active';
        $where['user_type']='Admin';
        $where['user_email != ']=!empty($loginData['role_id'])?getUserEmail($loginData['role_id']):'';
        $data["users_data"] = $this->c_model->getAllData("users", "id,user_email", $where);
        adminview('assign-menu', $data);
    }
}
