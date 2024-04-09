<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Teacher extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_teacher_master";
    }
    function index() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Employee Master";
        $data["title"] = "Employee List";
        $data['access'] = checkWriteMenus(getUri(2));
        adminview('view-teacher', $data);
    }
    function add_teacher() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Employee Master";
        $data["title"] = !empty($id) ? "Edit Employee" : "Add Employee";
        $data['access'] = checkWriteMenus(getUri(2));
        $data['schools'] = $this->c_model->getAllData("school_master", 'school_name,school_code', ['status' => 'Active']);
        $data['grades'] = $this->c_model->getAllData("grades", 'id,grade', ['status' => 'Active']);
        $savedData = $this->c_model->getSingle($this->table, '*', ['id' => $id]);
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['teacher_name'] = !empty($savedData['teacher_name']) ? $savedData['teacher_name'] : '';
        $data['aadhaar_number'] = !empty($savedData['aadhaar_number']) ? $savedData['aadhaar_number'] : '';
        $data['cmss_code'] = !empty($savedData['cmss_code']) ? $savedData['cmss_code'] : '';
        $data['employee_type'] = !empty($savedData['employee_type']) ? $savedData['employee_type'] : '';
        $data['grade'] = !empty($savedData['grade']) ? $savedData['grade'] : '';
        $data['school_code'] = !empty($savedData['school_code']) ? $savedData['school_code'] : '';
        $data['date_of_joining'] = !empty($savedData['date_of_joining']) ? $savedData['date_of_joining'] : '';
        $data['profile_image'] = !empty($savedData['profile_image']) ? $savedData['profile_image'] : '';
        $data['master_file'] = !empty($savedData['master_file']) ? $savedData['master_file'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-teacher', $data);
    }
    public function save_teacher() { 
        $post = $this->request->getVar();
        
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
    
        $school = !empty($post['school']) ? explode('/', $post['school']) : [];
        $data['aadhaar_number'] = !empty($post['aadhaar_number'])?trim($post['aadhaar_number']):'';
        $data['employee_type'] = !empty($post['employee_type'])?trim($post['employee_type']):'';
        $url = !empty($id) ? "?id=" . $id : "";
        $duplicate = $this->c_model->getSingle($this->table, 'id', $data);
        if ($duplicate && (empty($id) || $duplicate['id'] !== $id)) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Entry';
            echo json_encode($response);
            exit;
        }
        if ($file = $this->request->getFile('profile_image')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $profile_image = $file->getRandomName();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_profile_image']) && file_exists(ROOTPATH . 'uploads/' . $post['old_profile_image'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_profile_image']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . '/uploads/' . $profile_image);
                $data['profile_image'] = $profile_image;
            }
        }
        if ($file = $this->request->getFile('file')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                $fileSizeInBytes = $file->getSize();
                $fileSizeInMB = round($fileSizeInBytes / (1024 * 1024)); // Calculate file size in MB
                // echo $fileSizeInMB;exit;
                if ($fileSizeInMB > 500) {
                    $response['status'] = false;
                    $response['message'] = 'File size should be less than 500 MB';
                    echo json_encode($response);
                    exit;
                }
                $ext = $file->guessExtension();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_file']) && file_exists(ROOTPATH . 'uploads/' . $post['old_file'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_file']);
                }
                if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
                    $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                    $data['master_file'] = $filename;
                    $data['master_file_upload_date'] = date('Y-m-d H:i:s');
                } else if ($ext == "pdf") {
                    $file->move(ROOTPATH . 'uploads', $filename);
                    $data['master_file'] = $filename;
                    $data['master_file_upload_date'] = date('Y-m-d H:i:s');
                } else {
                    $response['status'] = false;
                    $response['message'] = 'This file type not allowed';
                    echo json_encode($response);
                    exit;
                }
            }
        }
        $profile_image=!empty($data['profile_image'])?$data['profile_image']:'';
        $master_file=!empty($data['master_file'])?$data['master_file']:'';
        $data['school_code'] = $school[0]??'';
        $data['school_name'] = $school[1]??'';
        $data['teacher_name'] = !empty($post['teacher_name'])?ucwords(trim($post['teacher_name'])):'';
        $data['cmss_code'] = !empty($post['cmss_code'])?trim($post['cmss_code']):'';
        $data['grade'] = !empty($post['grade'])?trim($post['grade']):'';
        $data['date_of_joining'] = date('Y-m-d', strtotime(trim($post['date_of_joining'])));
        $data['status'] = !empty($post['status'])?trim($post['status']):'';
       
        $act_type = '';
        $act_details = '';
        if (empty($id)) {
            $act_type = 'Added A Employee';
            $act_details = 'Name [' . $data['teacher_name'] . ']<br>Aadhaar Number [' . $data['aadhaar_number'] . ']<br>Employee Type [' . $data['employee_type'] . ']<br>Profile Image [' . $profile_image . ']<br>Master File [' . $master_file . ']<br>School Code [' . $data['school_code'] . ']<br>School Name [' . $data['school_name'] . ']<br>CMSS Code [' . $data['cmss_code'] . ']<br>Grade [' . $data['grade'] . ']<br>Date Of Joining [' . $data['date_of_joining'] . ']<br>Status [' . $data['status'] . ']<br>';
        } else if (!empty($id)) {
            $check = $this->c_model->getSingle($this->table, 'teacher_name,aadhaar_number,employee_type,profile_image,status,date_of_joining,grade,master_file,school_code,school_name,cmss_code', ['id' => $id]);
            $act_type = 'Edited A Employee';
            if ($check['teacher_name'] != $data['teacher_name']) {
                $act_details.= 'From [' . $check['teacher_name'] . '] to [' . $data['teacher_name'] . ']<br>';
            }
            if ($check['aadhaar_number'] != $data['aadhaar_number']) {
                $act_details.= 'From [' . $check['aadhaar_number'] . '] to [' . $data['aadhaar_number'] . ']<br>';
            }
            if ($check['employee_type'] != $data['employee_type']) {
                $act_details.= 'From [' . $check['employee_type'] . '] to [' . $data['employee_type'] . ']<br>';
            }
            if (!empty($check['profile_image']) || !empty($profile_image) && $check['profile_image'] != $profile_image) {
            $act_details .= 'Profile image changed from [' . $check['profile_image'] . '] to [' . $profile_image . '] of Employee '.$data['teacher_name'].', CMSS Code '.$data['cmss_code'].'<br>';
            }
            if ($check['status'] != $data['status']) {
                $act_details.= $data['teacher_name'].' From [' . $check['status'] . '] to [' . $data['status'] . ']<br>';
            }
            if ($check['date_of_joining'] != $data['date_of_joining']) {
                $act_details.= 'From [' . $check['date_of_joining'] . '] to [' . $data['date_of_joining'] . ']<br>';
            }
            if ($check['grade'] != $data['grade']) {
                $act_details.= 'From [' . $check['grade'] . '] to [' . $data['grade'] . ']';
            }
            if ((!empty($check['master_file']) || !empty($master_file)) && $check['master_file'] != $master_file) {
                $act_details .= 'Master File changed from [' . $check['master_file'] . '] to [' . $master_file . '] of Employee ' . $data['teacher_name'] . ', CMSS Code ' . $data['cmss_code'] . '<br>';
            }
            if ($check['school_code'] != $data['school_code']) {
                $act_details.= 'From [' . $check['school_code'] . '] to [' . $data['school_code'] . ']<br>';
            }
            if ($check['school_name'] != $data['school_name']) {
                $act_details.= 'From [' . $check['school_name'] . '] to [' . $data['school_name'] . ']<br>';
            }
            if ($check['cmss_code'] != $data['cmss_code']) {
                $act_details.= 'From [' . $check['cmss_code'] . '] to [' . $data['cmss_code'] . ']<br>';
            }
        }
        if(!empty($act_details)){
            addUserLog($act_type, $act_details);
        }
        $last_id = '';
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
        $url = base_url(ADMINPATH . 'add-employee') . '?id=' . $last_id;
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
            $where[" school_name LIKE '%" . $searchString . "%' OR school_code LIKE '%" . $searchString . "%' OR teacher_name LIKE '%" . $searchString . "%'  OR aadhaar_number LIKE '%" . $searchString . "%'  OR cmss_code LIKE '%" . $searchString . "%' OR grade LIKE '%" . $searchString . "%' OR date_of_joining LIKE '%" . $searchString . "%'"] = null;
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
        $select = '*,DATE_FORMAT(add_date , "%d-%m-%Y %r") AS add_date,DATE_FORMAT(update_date , "%d-%m-%Y %r") AS update_date,DATE_FORMAT(date_of_joining , "%d-%m-%Y") AS date_of_joining';
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
    public function view_teacher_detail() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data['access'] = checkWriteMenus(getUri(2));
        $data["menu"] = "Teacher Detail";
        $data["title"] = "Teacher Detail";
        $data['id'] = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : "";
        $data['type'] = !empty($this->request->getVar('type')) ? $this->request->getVar('type') : "Job";
        $data["details"] = $this->c_model->getSingle($this->table, '*', ['id' => $data['id']]);
        $data["file"] = !empty($data["details"]['master_file']) ? $data["details"]['master_file'] : "";
        $data['records'] = $this->c_model->getAllData("records", 'id,financial_year,filename,file', ['status' => 'Active', 'teacher_id' => $data['id'], 'record_type' => $data['type']]);
        adminview('teacher-detail', $data);
    }
    public function save_master_file() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $url = !empty($this->request->getVar('url')) ? $this->request->getVar('url') : '';
        if ($file = $this->request->getFile('file')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                $ext = $file->guessExtension();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_file']) && file_exists(ROOTPATH . 'uploads/' . $post['old_file'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_file']);
                }
                if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
                    $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                    $data['master_file'] = $filename;
                    $data['master_file_update_date'] = date('Y-m-d H:i:s');
                } else if ($ext == "pdf") {
                    $file->move(ROOTPATH . 'uploads', $filename);
                    $data['master_file'] = $filename;
                    $data['master_file_update_date'] = date('Y-m-d H:i:s');
                } else {
                    $this->session->setFlashdata('failed', 'This Type file is not allowed');
                    return redirect()->to(base_url(ADMINPATH . 'records-list'));
                }
            }
        }
        $check = $this->c_model->getSingle($this->table, 'master_file', ['id' => $id]);
        $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
        $act_type = '';
        $act_details = '';
        if (!empty($check['master_file']) && !empty($data['master_file']) && $check['master_file'] != $data['master_file']) {
        $act_type='Master File Updated';
        $act_details .= 'Changed from [' . $check['master_file'] . '] to <br>[' . $data['master_file'] . ']<br>';
        }
        if(!empty($act_details)){
            addUserLog($act_type, $act_details);
        }
        return redirect()->to($url);
    }
}
