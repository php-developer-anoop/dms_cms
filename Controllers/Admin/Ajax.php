<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Ajax extends BaseController {
    protected $c_model;
    protected $session;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
    }
    public function index() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, null, ['id' => $id]);
        if (!empty($records)) {
            if ($table == "dt_users") {
                $user = $this->c_model->getSingle($table, 'user_email,user_name', ['id' => $id]);
                $act_type = 'Deleted a User';
                $act_details = 'Email [' . $user['user_email'] . ']';
                addUserLog($act_type, $act_details);
            }
            if ($table == "dt_records") {
                $record = $this->c_model->getSingle($table, 'filename,file,teacher_name,record_type,cmss_code,financial_year', ['id' => $id]);
                $act_type = 'Deleted a Record';
                $act_details = 'File title [' . $record['filename'] . ']<br>';
                $act_details.= 'File [' . $record['file'] . ']<br>';
                $act_details.= 'Employee [' . $record['teacher_name'] . ']<br>';
                $act_details.= 'CMSS Code [' . $record['cmss_code'] . ']<br>';
                $act_details.= 'Record Type [' . $record['record_type'] . ']<br>';
                $act_details.= 'Session Year [' . $record['financial_year'] . ']<br>';
                addUserLog($act_type, $act_details);
            }
            $result = $this->c_model->deleteRecords($table, ['id' => $id]);
        }
    }
    public function changeStatus() {
        $id = !empty($this->request->getVar("id")) ? $this->request->getVar("id") : "";
        $table = !empty($this->request->getVar("table")) ? $this->request->getVar("table") : "";
        $records = $this->c_model->getSingle($table, 'status', ['id' => $id]);
        if (!empty($records)) {
            $current_status = $records['status'];
            if ($current_status == "Active") {
                $data['status'] = "Inactive";
            } else {
                $data['status'] = "Active";
            }
            if ($table == "dt_users") {
                $user = $this->c_model->getSingle($table, 'user_email', ['id' => $id]);
                $act_type = 'Changes Status';
                $act_details = 'Email [' . $user['user_email'] . '] To ' . $data['status'];
                addUserLog($act_type, $act_details);
            }
            if ($table == "dt_school_master") {
                $user = $this->c_model->getSingle($table, 'school_code', ['id' => $id]);
                $act_type = 'Changes Status';
                $act_details = 'School Code [' . $user['school_code'] . '] To ' . $data['status'];
                addUserLog($act_type, $act_details);
            }
            if ($table == "dt_grades") {
                $user = $this->c_model->getSingle($table, 'grade', ['id' => $id]);
                $act_type = 'Changes Status';
                $act_details = 'Grade [' . $user['grade'] . '] To ' . $data['status'];
                addUserLog($act_type, $act_details);
            }
            $this->c_model->updateRecords($table, $data, ['id' => $id]);
            echo $data['status'];
        }
    }
    public function getSlug() {
        $keyword = $this->request->getVar("keyword");
        if (empty($keyword)) {
            return '';
        }
        $slug = validate_slug($keyword);
        return $slug;
    }
    public function getCount() {
        $type = $this->request->getVar('type') ??"";
        $table = $this->request->getVar('table') ??"";
        $where = [];
        $where['status'] = 'Active';
        if ($type == "users") {
            $where['user_type'] = 'Admin';
        }
        $count = count_data('id', $table, $where);
        echo $count;
    }
    public function checkDuplicateUser() {
        $email_id = !empty($this->request->getVar('email_id')) ? $this->request->getVar('email_id') : "";
        $where = [];
        $where = ['status' => 'Active', 'user_type' => 'Admin', 'user_email' => $email_id];
        $data = $this->c_model->getSingle("users", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicateSchool() {
        $school_code = !empty($this->request->getVar('school_code')) ? $this->request->getVar('school_code') : "";
        $where = [];
        $where['status'] = 'Active';
        $where['school_code'] = $school_code;
        $data = $this->c_model->getSingle("school_master", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicateYear() {
        $startYear = !empty($this->request->getVar('startYear')) ? $this->request->getVar('startYear') : "";
        $endYear = !empty($this->request->getVar('endYear')) ? $this->request->getVar('endYear') : "";
        $where = [];
        $where['status'] = 'Active';
        $where['financial_year'] = $startYear . ' - ' . $endYear;
        $data = $this->c_model->getSingle("financial_years", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function checkDuplicateTeacher() {
        $val = !empty($this->request->getVar('val')) ? $this->request->getVar('val') : "";
        $type = !empty($this->request->getVar('type')) ? $this->request->getVar('type') : "";
        $where = [];
        // $where['status'] = 'Active';
        if ($type == 'cms_code') {
            $where = ['cmss_code LIKE' => '%' . $val . '%'];
        } else if ($type == "aadhaar_number") {
            $where = ['aadhaar_number LIKE' => '%' . $val . '%'];
        }
        $data = $this->c_model->getSingle("teacher_master", 'id', $where);
        if ($data) {
            echo "yes";
        }
    }
    public function assign_menus() {
        $response = [];
        if ($this->request->getMethod() != "post") {
            $response['status'] = false;
            $response['message'] = 'Invalid Request ';
            echo json_encode($response);
            exit;
        }
        $post = $this->request->getVar();
        $id = $post['user'] ? $post['user'] : '';
        if (empty($post['user'])) {
            $response['status'] = false;
            $response['message'] = "Please Choose An Admin";
            echo json_encode($response);
            exit;
        }
        $data = [];
        $data = ['read_menu_ids' => !empty($post['read']) ? implode(",", $post['read']) : '', 'write_menu_ids' => !empty($post['write']) ? implode(",", $post['write']) : ''];
        $this->c_model->updateRecords("users", $data, ['id' => $id]);
    }
    public function assignmenu() {
        $response = [];
        if ($this->request->getMethod() != "post") {
            $response['status'] = false;
            $response['message'] = 'Invalid Request ';
            echo json_encode($response);
            exit;
        }
        $post = $this->request->getVar();
        $id = $post['user'] ? $post['user'] : '';
        if (empty($post['user'])) {
            $response['status'] = false;
            $response['message'] = "Please Choose An Admin";
            echo json_encode($response);
            exit;
        }
        if ($post['type'] == "read") {
            $data = ['read_menu_ids' => !empty($post['read']) ? implode(",", $post['read']) : '' ];
        }
        if ($post['type'] == "write") {
            $data = ['write_menu_ids' => !empty($post['write']) ? implode(",", $post['write']) : ''];
        }
        $this->c_model->updateRecords("users", $data, ['id' => $id]);
    }
    public function getTeacherName() {
        $val = $this->request->getVar("val");
        $query = "SELECT id, teacher_name, cmss_code FROM dt_teacher_master WHERE (cmss_code LIKE ? OR teacher_name LIKE ?)";
        $teachers = db()->query($query, ["%" . $val . "%", "%" . $val . "%"])->getResultArray();
        if (empty($teachers)) {
            echo "No Record Found";
            exit;
        } else {
            foreach ($teachers as $key => $value) {
                echo "<li value=" . $value['id'] . ">" . $value['teacher_name'] . '/' . $value['cmss_code'] . "</li>";
            }
        }
    }
    public function getTeacherDetail() {
        $teacher_id = !empty($this->request->getVar("teacher_id")) ? $this->request->getVar("teacher_id") : "";
        if (!empty($teacher_id)) {
            $data = $this->c_model->getSingle("teacher_master", '*', ['id' => $teacher_id]);
            $html = [];
            if (!empty($data)) {
                $image = !empty($data['profile_image']) ? base_url('uploads/' . $data['profile_image']) : "";
                $master_file = !empty($data['master_file']) ? base_url('uploads/' . $data['master_file']) : "";
                $html['content'] = '<div class="profiletxt">
        <ul>
        <input type="hidden" id="teachname" value="' . $data['teacher_name'] . '">
        <input type="hidden" id="cmscode" value="' . $data['cmss_code'] . '">
          <li ><span>Teacher name:</span>' . $data['teacher_name'] . '</li>
          <li><span>School name:</span>' . $data['school_name'] . '</li>
          <li><span>CMSS Code:</span>' . $data['cmss_code'] . '</li>
          <li><span>Grade:</span>' . $data['grade'] . '</li>
          <li><span>Aadhaar number:</span>' . $data['aadhaar_number'] . '</li>
          <li><span>Date of Joining:</span>' . $data['date_of_joining'] . '</li>
        </ul>
      </div>
      <div class="prfl-img">
        <img src="' . $image . '">
      </div>';
                $html['master_file_name'] = !empty($data['master_file']) ? $data['master_file'] : "";
                $html['master_file'] = $master_file;
                $html['name_cms'] = $data['teacher_name'].'/'.$data['cmss_code'];
                $html['mfupload_date'] = !is_null($data['master_file_upload_date']) ? date('d-m-Y', strtotime($data['master_file_upload_date'])) : "";
                $html['mfupd_date'] = !is_null($data['master_file_update_date']) ? date('d-m-Y', strtotime($data['master_file_update_date'])) : "";
            }
            echo json_encode($html);
        }
    }
    public function getRecords() {
        $loginData = $this->session->get('login_data');
        $user_type = $loginData['role'];
        $access = checkWriteMenus('dashboard');
        $teacher_id = $this->request->getVar("teacher_id") ??"";
        $financial_year = $this->request->getVar("financial_year") ??"";
        $record_type = $this->request->getVar("record_type") ??"";
        $type = str_replace("Record", "", $record_type);
        if ($teacher_id && $financial_year && $type) {
            $data = $this->c_model->getAllData("records", 'id,filename,size,file,add_date', ['teacher_id' => $teacher_id, 'financial_year' => $financial_year, 'record_type' => $type]);
            $html = '';
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $size = !empty($value['size']) ? $value['size'] . ' KB' : "";
                    $file = !empty($value['file']) ? base_url('uploads/' . $value['file']) : "";
                    $url = base_url(ADMINPATH . 'add-records?id=') . $value['id'];
                    $html.= '<tr>';
                    $html.= '<td>' . $value['filename'] . '</td>';
                    $html.= '<td>' . date('d-m-Y', strtotime($value['add_date'])) . '</td>';
                    $html.= '<td>' . $size . '</td>';
                    $html.= ($access || $user_type != "Admin") ? '<td><a href="' . $url . '"><img src="' . base_url() . 'uploads/edits.png"></a></td>' : '';
                    $html.= '<td><a href="javascript:void(0)" onclick="appendrecordfile(\'' . $file . '\')" data-bs-toggle="modal" data-bs-target="#recordModal"><img src="' . base_url() . 'uploads/eyes.png"></a></td>';
                    $html.= '</tr>';
                }
            }
            return $html;
            
        }
    }
    public function save_record() {
        $data = [];
        $post = $this->request->getVar();
        $financial_year = !empty($post['fin_year']) ? $post['fin_year'] : "";
        $teacher_name = !empty($post['teach_name']) ? $post['teach_name'] : "";
        $cmss_code = !empty($post['cmsscode']) ? $post['cmsscode'] : "";
        $record_type = !empty($post['jobtype']) ? $post['jobtype'] : "";
        $filetitle = !empty($post['filetitle']) ? $post['filetitle'] : "";
        $teacher = $this->c_model->getSingle("teacher_master", 'id', ['cmss_code' => $cmss_code]);
        $teacher_id = $teacher['id'];
        if ($file = $this->request->getFile('file')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileSizeInBytes = $file->getSize();
                $fileSizeInKB = round($fileSizeInBytes / 1024);
                $filename = $file->getRandomName();
                $ext = $file->guessExtension();
                $data['size'] = $fileSizeInKB;
                if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
                    $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                    $data['file'] = $filename;
                } else if ($ext == "pdf") {
                    $file->move(ROOTPATH . 'uploads/', $filename);
                    $data['file'] = $filename;
                } else {
                    $this->session->setFlashdata('failed', 'This Type file is not allowed');
                    return redirect()->to($post['url']);
                }
            }
        }
        $data['financial_year'] = trim($financial_year);
        $data['teacher_name'] = trim($teacher_name);
        $data['teacher_id'] = trim($teacher_id);
        $data['cmss_code'] = trim($cmss_code);
        $data['record_type'] = trim($record_type);
        $data['filename'] = trim($filetitle);
        $data['add_date'] = date('Y-m-d H:i:s');
        $act_type = '';
        $act_details = '';
       
        $act_type = 'Added A Record';
        $act_details = 'Session Year [' . $data['financial_year'] . ']<br>File Name [' . $data['filename'] . ']<br>CMSS Code [' . $data['cmss_code'] . ']<br>Employee Name [' . $data['teacher_name'] . ']<br>Employee Id [' . $data['teacher_id'] . ']<br>Record Type [' . $data['record_type'] . ']';
        if(!empty($act_details)){
            addUserLog($act_type, $act_details);
        }
        $this->c_model->insertRecords("records", $data);
        return redirect()->to($post['url']);
    }
    public function getTeachersList() {
        $school_code = !empty($this->request->getVar('school_code')) ? $this->request->getVar('school_code') : "";
        $where = [];
        //$where['status'] = 'Active';
        $where['school_code'] = $school_code;
        $data = $this->c_model->getAllData("teacher_master", 'id,teacher_name,cmss_code', $where);
        $html = '<option value="">--Select Employee--</option>';
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $html.= '<option  value="' . $value['id'] . '">' . $value['teacher_name'] . '/' . $value['cmss_code'] . '</option>';
            }
        }
        echo $html;
    }
    
    public function add_user_log(){
        $activity_type = !empty($this->request->getVar('activity_type')) ? $this->request->getVar('activity_type') : "";
        $details = !empty($this->request->getVar('details')) ? $this->request->getVar('details') : "";
        
        if(!empty($activity_type) && !empty($details)){
            addUserLog($activity_type,$details);
        }
    }
    public function addDownloadActivity() {
    $master_file = $this->request->getVar('master_file'); // Using null coalescing operator to set default value
    $name_cms = $this->request->getVar('name_cms'); 
    if (!empty($master_file)) { // Checking if master file is not empty before proceeding
        $activity_type = 'Downloaded Master File';
        $details = 'Downloaded Master File named ' . $master_file.' of '.$name_cms;
        
        addUserLog($activity_type, $details);
    }
}

    
}
