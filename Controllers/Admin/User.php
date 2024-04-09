<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class User extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_users";
    }

    function index() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Users Master";
        $data["title"] = "Users List";
        $data['access'] = checkWriteMenus(getUri(2));
        adminview('view-user', $data);
    }

    function add_user() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Users Master";
        $data["title"] = !empty($id) ? "Edit User" : "Add User";
        $data['access'] = checkWriteMenus(getUri(2));
        $savedData = $this->c_model->getSingle($this->table, 'id,user_name,user_email,user_phone,status', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['user_name'] = !empty($savedData['user_name']) ? $savedData['user_name'] : '';
        $data['user_email'] = !empty($savedData['user_email']) ? $savedData['user_email'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-user', $data);
    }

    public function save_user() {
        $response = [];
        $post = $this->request->getVar();
        $id = !empty($post['id']) ? $post['id'] : '';
        $data = [];
        $data['user_email'] = trim($post['user_email']);
        //check email domain in entered email address
        if(!empty($post['user_email'])){
               $explodeEmailAddress  = explode('@',$post['user_email']);
               if( !empty($explodeEmailAddress[1]) && $explodeEmailAddress[1] != 'cmseducation.org' ){
                    $response['status'] = false;
                    $response['message'] = 'Please provide official email address that domain name should be end with cmseducation.org';
                    echo json_encode($response);
                    exit;
               }
        }

        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && (empty($id) || $duplicate['id'] !== $id)) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Entry';
            echo json_encode($response);
            exit;
        }
        $data['user_name'] = trim($post['user_name']);
        $data['user_type'] = 'Admin';
        if (empty($id)) {
            $password = generate_password(10);
            $data['raw_password'] = $password;
            $data['enc_password'] = md5($password);
            sendEmail($data['user_email'], $data['user_email'], $password);
        }
        
        $last_id = '';
        $message = '';
        $act_type = '';
        $act_details = '';
        
        if (empty($id)) {
            $act_type = 'Added A New User';
            $act_details = 'Username [' . $data['user_name'] . '], Email [' . $data['user_email'] . ']';
        } else if (!empty($id)) {
            $check = $this->c_model->getSingle($this->table, 'user_name,user_email', ['id' => $id]);
            $act_type = 'Edited A User';
        
            if ($check['user_name'] != $data['user_name']) {
                $act_details .= 'Username changed from [' . $check['user_name'] . '] to [' . $data['user_name'] . ']<br>';
            }
        
            if ($check['user_email'] != $data['user_email']) {
                $act_details .= 'Email changed from [' . $check['user_email'] . '] to [' . $data['user_email'] . ']';
            }
        }
        if(!empty($act_details)){
            addUserLog($act_type, $act_details);
        }
        

        if (empty($id)) {
            $data['add_date'] = date('Y-m-d H:i:s');
            $data['status'] = 'Active';
            $last_id = $this->c_model->insertRecords($this->table, $data);
            $message = 'Data Added Successfully';
        } else {
            $data['update_date'] = date('Y-m-d H:i:s');
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $last_id = $id;
            $message = 'Data Updated Successfully';
        }
        $url = base_url(ADMINPATH . 'add-user') . '?id=' . $last_id;
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
        $where['user_type'] = 'Admin';
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where['user_type'] = 'Admin';
            $where[" user_name LIKE '%" . $searchString . "%' OR user_email LIKE '%" . $searchString . "%' OR user_phone LIKE '%" . $searchString . "%'"] = null;
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
    public function assign_menu() {
        $data = [];
        $uri = service('uri');
        $data["title"] = "Assign Menu";
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
        if (!empty($id)) {
            $data['department'] = $this->c_model->getSingle("department", 'read_menu_ids,write_menu_ids', ['id' => $id]);
        } else {
            $data['department'] = [];
        }
        $data['id'] = $id;
        $data["menu_data"] = $this->c_model->getAllData("menus", "*,DATE_FORMAT(add_date,'%d-%m-%Y %r')", ['status' => 'Active']);
        $data["department_data"] = $this->c_model->getAllData("department", "id,department_name", ['status' => 'Active']);
        adminview('assign-menu', $data);
    }
    public function change_password() {
        $data["title"] = "Change Password";
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
        $data['id'] = $id;
        adminview('change-password', $data);
    }
    public function update_password() {
        $post = $this->request->getVar();
        $password = $post['cpassword'];
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
        $email = $this->c_model->getSingle($this->table, 'user_email', ['id' => $id]);
        $this->c_model->updateRecords($this->table, ['raw_password' => $password, 'enc_password' => md5($password) ], ['id' => $id]);
        $act_type = 'Change Password';
        $act_details = 'Email ['.$email['user_email'].']';
        
        addUserLog($act_type, $act_details);
        sendEmailForgotPassword($email['user_email'], $email['user_email'], $password);
        $this->session->setFlashdata('success', 'Password Changed Successfully');
        return redirect()->to(base_url(ADMINPATH . 'user-list'));
    }
}
