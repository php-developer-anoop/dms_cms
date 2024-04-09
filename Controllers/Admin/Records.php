<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Records extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_records";
    }
    function index() {
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Records Master";
        $data["title"] = "Records List";
        $data['access'] = checkWriteMenus(getUri(2));
        $data['teacher_id'] = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data['record_type'] = !empty($this->request->getVar('record_type')) ? $this->request->getVar('record_type') : '';
        $data['teacher_name'] = !empty($this->request->getVar('id')) ? getTeacherNameCmsCode($data['teacher_id']) : "";
        adminview('view-records', $data);
    }
    function add_record() {
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data["menu"] = "Records Master";
        $data["title"] = !empty($id) ? "Edit Records" : "Add Records";
        $data['access'] = checkWriteMenus(getUri(2));
        $savedData = $this->c_model->getSingle($this->table, 'id,financial_year,teacher_id,cmss_code,teacher_name,record_type,file,filename,status', ['id' => $id]);
        $data['years'] = $this->c_model->getAllData("financial_years", 'id,financial_year', ['status' => 'Active'], null, null, 'DESC', 'id');
        $data['id'] = !empty($savedData['id']) ? $savedData['id'] : $id;
        $data['teacher_id'] = !empty($savedData['teacher_id']) ? $savedData['teacher_id'] : '';
        $teacher_name = !empty($savedData['teacher_name']) ? $savedData['teacher_name'] : '';
        $cmss_code = !empty($savedData['cmss_code']) ? $savedData['cmss_code'] : '';
        $data['teacher_name'] = !empty($teacher_name && $cmss_code) ? $teacher_name . '/' . $cmss_code : '';
        $data['financial_year'] = !empty($savedData['financial_year']) ? $savedData['financial_year'] : '';
        $data['filename'] = !empty($savedData['filename']) ? $savedData['filename'] : '';
        $data['record_type'] = !empty($savedData['record_type']) ? $savedData['record_type'] : '';
        $data['file'] = !empty($savedData['file']) ? $savedData['file'] : '';
        $data['status'] = !empty($savedData['status']) ? $savedData['status'] : 'Active';
        adminview('add-records', $data);
    }
    public function save_record() {
        $post = $this->request->getVar();
        
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        $teacher = !empty($post['teacher_name']) ? explode('/', $post['teacher_name']) : [];
        $data['financial_year'] = trim($post['financial_year']);
        $data['filename'] = trim($post['filename']);
        $data['cmss_code'] = $teacher[1]??'';
        $data['teacher_name'] = $teacher[0]??'';
        $data['teacher_id'] = trim($post['teacher_id']);
        $data['record_type'] = trim($post['record_type']);
        $url = !empty($id) ? "?id=" . $id : "";
        if ($file = $this->request->getFile('file')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileSizeInBytes = $file->getSize();
                $fileSizeInKB = round($fileSizeInBytes / 1024);
                $filename = $file->getRandomName();
                $ext = $file->guessExtension();
                if (is_file(ROOTPATH . 'uploads/' . $post['old_file']) && file_exists(ROOTPATH . 'uploads/' . $post['old_file'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_file']);
                }
                $data['size'] = $fileSizeInKB;
                if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
                    $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                    $data['file'] = $filename;
                } else if ($ext == "pdf") {
                    $file->move(ROOTPATH . 'uploads/', $filename);
                    $data['file'] = $filename;
                } else {
                    $response['status'] = false;
                    $response['message'] = 'This file type not allowed';
                    echo json_encode($response);
                    exit;
                }
            }
        }
        $file=!empty($data['file'])?$data['file']:'';
        $data['status'] = 'Active';
        $act_type = '';
        $act_details = '';
        if (empty($id)) {
            $act_type = 'Added A Record';
            $act_details = 'Session Year [' . $data['financial_year'] . ']<br>File Name [' . $data['filename'] . ']<br>CMSS Code [' . $data['cmss_code'] . ']<br>Employee Name [' . $data['teacher_name'] . ']<br>Employee Id [' . $data['teacher_id'] . ']<br>Record Type [' . $data['record_type'] . ']';
        } else if (!empty($id)) {
            $check = $this->c_model->getSingle($this->table, 'financial_year,filename,cmss_code,teacher_name,teacher_id,record_type,file', ['id' => $id]);
            $act_type = 'Edited A Record';
            if ($check['financial_year'] != $data['financial_year']) {
                $act_details.= 'Session Year From [' . $check['financial_year'] . '] to [' . $data['financial_year'] . ']<br>';
            }
            if ($check['filename'] != $data['filename']) {
                $act_details.= 'Filename From [' . $check['filename'] . '] to [' . $data['filename'] . ']';
            }
            if (!empty($check['file']) && !empty($data['file']) && $check['file'] != $data['file']) {
                $act_details.= 'Record file changed from [' . $check['file'] . '] to [' . $data['file'] . '] of Employee '.$check['teacher_name'].' , CMSS Code '.$check['cmss_code'];
            }
            if ($check['cmss_code'] != $data['cmss_code']) {
                $act_details.= 'CMSS Code From [' . $check['cmss_code'] . '] to [' . $data['cmss_code'] . ']';
            }
            if ($check['teacher_name'] != $data['teacher_name']) {
                $act_details.= 'Employee Name From [' . $check['teacher_name'] . '] to [' . $data['teacher_name'] . ']';
            }
            if ($check['teacher_id'] != $data['teacher_id']) {
                $act_details.= 'Employee Id From [' . $check['teacher_id'] . '] to [' . $data['teacher_id'] . ']';
            }
            if ($check['record_type'] != $data['record_type']) {
                $act_details.= 'Record Type From [' . $check['record_type'] . '] to [' . $data['record_type'] . ']';
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
        $url = base_url(ADMINPATH . 'add-records') . '?id=' . $last_id;
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
        if (!empty($post['id'])) {
            $where["teacher_id"] = trim($post['id']);
        }
        if (!empty($post['record_type'])) {
            $where["record_type"] = trim($post['record_type']);
        }
        $searchString = null;
        if (!empty($get["search"]["value"])) {
            $searchString = trim($get["search"]["value"]);
            $where[" teacher_name LIKE '%" . $searchString . "%' OR financial_year LIKE '%" . $searchString . "%' OR cmss_code LIKE '%" . $searchString . "%'"] = null;
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
        $url = base_url('uploads/');
        $select = '*, CONCAT("' . $url . '", file) as filepreview, DATE_FORMAT(add_date, "%d-%m-%Y %r") AS add_date, DATE_FORMAT(update_date, "%d-%m-%Y %r") AS update_date';
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
