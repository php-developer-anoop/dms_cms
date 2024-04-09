<?php
namespace App\Controllers;
class Home extends BaseController {
    protected $session;
    public function __construct() {
        $this->session = session();
    }
    public function index() {
        $data = [];
        $session = $this->session->get('login_data');
        $company = db()->table('dt_websetting')->select('favicon')->get()->getRowArray();
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        if ($session) {
            return redirect()->to(base_url(ADMINPATH . "dashboard"));
        } else {
            return view('welcome_message', $data);
        }
    }
}
