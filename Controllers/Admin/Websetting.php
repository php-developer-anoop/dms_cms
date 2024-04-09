<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Websetting extends BaseController {
    protected $c_model;
    protected $session;
    protected $table;
    public function __construct() {
        $this->c_model = new Common_model();
        $this->session = session();
        $this->table = "dt_websetting"; 
    }
    public function index() {
        $data = [];
        $data['title'] = 'Web Setting';
        $loginData = $this->session->get('login_data');
        $data['user_type']=$loginData['role'];
        $data['access']=checkWriteMenus(getUri(2));
        $data['web'] = $this->c_model->getSingle($this->table, '*');
        adminview('websetting', $data);
    }
    public function save_setting() {
        $post = $this->request->getVar();
        $id = !empty($this->request->getVar('id')) ? $this->request->getVar('id') : '';
        $data = [];
        if ($file = $this->request->getFile('logo')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                if (is_file('uploads/' . $post['old_logo']) && file_exists(ROOTPATH . 'uploads/' . $post['old_logo'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_logo']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                $data['logo'] = $filename;
            }
        }
        if ($file = $this->request->getFile('favicon')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $filename = $file->getRandomName();
                if (is_file('uploads/' . $post['old_favicon']) && file_exists(ROOTPATH . 'uploads/' . $post['old_favicon'])) {
                    @unlink(ROOTPATH . 'uploads/' . $post['old_favicon']);
                }
                $image = \Config\Services::image()->withFile($file)->save(ROOTPATH . 'uploads/' . $filename);
                $data['favicon'] = $filename;
            }
        }
        $data['company_name'] = trim($post['company_name']);
        $data['care_whatsapp_no'] = trim($post['care_whatsapp_no']);
        $data['care_email_id'] = trim($post['care_email_id']);
        $data['care_mobile_no'] = trim($post['care_mobile_no']);
        //$data['map_script'] = trim($post['map_script']);
        $data['office_address'] = trim($post['office_address']);
        if (empty($id)) {
            $this->c_model->insertRecords($this->table, $data);
            $this->session->setFlashdata('success', 'Data Added Successfully ');
        } else {
            $this->c_model->updateRecords($this->table, $data, ['id' => $id]);
            $this->session->setFlashdata('success', 'Data Updated Successfully');
        }
        return redirect()->to(base_url(ADMINPATH . 'websetting'));
    }
}
?>