<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Dashboard extends BaseController
{
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_school_master";
    }
    public function index(){
        
        $data['title']='Dashboard';
        $loginData = $this->session->get('login_data');
        $data['user_type'] = $loginData['role'];
        $data['access'] = checkWriteMenus('dashboard');
        $data['school_code'] = !empty($this->request->getVar("school_code"))?$this->request->getVar("school_code"):""; // Default to empty string if not present
        $data['schools'] = $this->c_model->getAllData($this->table, 'id, school_name, school_code', ['status' => 'Active']);
        
        $where = ['status' => 'Active'];
        if (!empty($data['school_code'])) {
            $where['school_code'] = $data['school_code'];
        } elseif (!empty($data['schools']) && is_array($data['schools']) && isset($data['schools'][0]['school_code'])) {
            $where['school_code'] = $data['schools'][0]['school_code']; // Fetch first school's code
        }

        $data['teachers'] = $this->c_model->getAllData("teacher_master", 'id,teacher_name,cmss_code', $where);
        $data['years'] = $this->c_model->getAllData("financial_years", 'id,financial_year', ['status' => 'Active'],null,null,'ASC','start_year');
     
        adminview('dashboard',$data);
    }
}
?>