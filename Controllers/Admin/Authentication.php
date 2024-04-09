<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Common_model;
class Authentication extends BaseController {
    protected $c_model;
    protected $session;
    public function __construct() {
        $this->session = session();
        $this->c_model = new Common_model();
    }
    public function index() {
        $session = $this->session->get('login_data');
        if ($session) {
            return redirect()->to(base_url(ADMINPATH . "dashboard"));
        }
        $data["meta_title"] = "Admin";
        echo view("admin/login_form", $data);
    }
    public function authenticate() {
        $post = $this->request->getVar();
        $email = !empty($post["email"]) ? trim($post["email"]) : "";
        $password = !empty($post["password"]) ? trim($post["password"]) : "";
        if (empty($email)) {
            $this->session->setFlashdata("failed", "Failed! Email Can not be empty ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        if (empty($password)) {
            $this->session->setFlashdata("failed", "Failed! Password Can not be empty ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        }

        //update password hardly for superadmin only
        // if($email == 'superadmin_dms@gmail.com'){
        // $this->c_model->updateRecords('users', ['enc_password' => md5($password) ], ['user_email' => $email ]);
        // }

        $where = [];
        $select = "*";
        $where["user_email"] = $email;
        $where["enc_password"] = md5($password);
        $user = $this->c_model->getSingle('users', $select, $where);
        if (empty($user)) {
            $this->session->setFlashdata("failed", "Invalid Email Or Password ");
            return redirect()->to(base_url(ADMINPATH . "login"));
        } else if (!empty($user)) {
            if ($user['user_type'] == "Admin") {
                if ($user['status'] == "Inactive") {
                    $this->session->setFlashdata("failed", "Your Profile Is Currently Inactive");
                    return redirect()->to(base_url(ADMINPATH . "login"));
                } else if ($user['status'] == "Blocked") {
                    $this->session->setFlashdata("failed", "Your Profile Is Currently Blocked ");
                    return redirect()->to(base_url(ADMINPATH . "login"));
                }
            }
            if ($user['user_type'] != "Admin") {
                $allmenu = [];
                $all_menu = db()->table('dt_menus')->select('slug')->where('status', 'Active')->get()->getResultArray();
                foreach ($all_menu as $key => $value) {
                    $allmenu[] = $value['slug'];
                }
                $read = [];
                if (!empty($user["read_menu_ids"])) {
                    $readSlugs = $this->getMenuSlugsByIDs($user["read_menu_ids"]);
                    foreach ($readSlugs as $key => $value) {
                        $read[] = $value['slug'];
                    }
                }
                $write = [];
                if (!empty($user["write_menu_ids"])) {
                    $writeSlugs = $this->getMenuSlugsByIDs($user["write_menu_ids"]);
                    foreach ($writeSlugs as $key => $value) {
                        $write[] = $value['slug'];
                    }
                }
                $sess_data = ["role_id" => $user["id"], "role" => $user["user_type"], "role_user_email" => $user["user_email"], "role_user_phone" => $user["user_phone"], "role_user_name" => $user['user_name'], "read_menu_ids" => $user["read_menu_ids"], "write_menu_ids" => $user['write_menu_ids'], "last_login" => date('Y-m-d H:i:s'), 'login_time' => date('H:i:s'), 'login_time_format' => date('H:i:s A'), 'loggedIn' => true, 'read_slug' => $read, 'write_slug' => $write, 'allmenu' => $allmenu];
                $this->c_model->updateRecords('users', ['last_login' => date('Y-m-d H:i:s') ], ['id' => $user["id"]]);
                $this->session->set('login_data', $sess_data);
                return redirect()->to(ADMINPATH . "dashboard");
            }
            $email = $user["user_email"];
            $where = [];
            $select = "write_menu_ids,read_menu_ids";
            $where["user_email"] = $email;
            $checkuser = $this->c_model->getSingle('users', $select, $where);
            if ($checkuser['read_menu_ids'] == "" && $checkuser['write_menu_ids'] == "") {
                $this->session->setFlashdata("failed", "No Menu Assigned To You!!");
                return redirect()->to(base_url());
            }else if($checkuser['read_menu_ids'] == ""){
                $this->session->setFlashdata("failed", "No Read Permission Assigned!!");
                return redirect()->to(base_url());
            }
            $otp = rand(111111, 999999);
            $role_user = ["role_user_email" => $user["user_email"], "otp" => $otp];
            $this->session->set('role_user', $role_user);
            sendEmailOtp($user["user_email"], $user["user_email"], $otp);
            $this->c_model->updateRecords('users', ['otp_sent' => $otp], ['id' => $user["id"]]);
            return redirect()->to(ADMINPATH . "otp-verification");
        }
    }
    function otp_verification() {
        $session = $this->session->get('login_data');
        if ($session) {
            return redirect()->to(base_url(ADMINPATH . "dashboard"));
        }
        $data["meta_title"] = "OTP Verification";
        echo view("admin/otp-verify", $data);
    }
    function verifyOtp() {
        $otp = trim($this->request->getVar('otp'));
        $loginData = $this->session->get('role_user');
        $email = $loginData['role_user_email'];
        $sess_otp = $loginData['otp'];
        $return = [];
        if ($sess_otp == $otp) {
            $where = [];
            $select = "*";
            $where["user_email"] = $email;
            $user = $this->c_model->getSingle('users', $select, $where);
            $allmenu = [];
            $all_menu = db()->table('dt_menus')->select('slug')->where('status', 'Active')->get()->getResultArray();
            foreach ($all_menu as $key => $value) {
                $allmenu[] = $value['slug'];
            }
            $read = [];
            if (!empty($user["read_menu_ids"])) {
                $readSlugs = $this->getMenuSlugsByIDs($user["read_menu_ids"]);
                foreach ($readSlugs as $key => $value) {
                    $read[] = $value['slug'];
                }
            }
            $write = [];
            if (!empty($user["write_menu_ids"])) {
                $writeSlugs = $this->getMenuSlugsByIDs($user["write_menu_ids"]);
                foreach ($writeSlugs as $key => $value) {
                    $write[] = $value['slug'];
                }
            }
            $deviceInfo = $this->getBrowserName();
            $ipAddress = $this->request->getIPAddress();
            $setLoginActivity = $this->addLoginActivity($user['id'], $user['user_name'], $ipAddress, $deviceInfo);
            addUserLog('Login','Login');
            $sess_data = ["role_id" => $user["id"], "role" => $user["user_type"], "role_user_email" => $user["user_email"], "role_user_phone" => $user["user_phone"], "role_user_name" => $user['user_name'], "read_menu_ids" => $user["read_menu_ids"], "write_menu_ids" => $user['write_menu_ids'], "last_login" => date('Y-m-d H:i:s'), 'login_time' => date('H:i:s'), 'login_time_format' => date('H:i:s A'), 'loggedIn' => true, 'read_slug' => $read, 'write_slug' => $write, 'allmenu' => $allmenu];
            $this->c_model->updateRecords('users', ['last_login' => date('Y-m-d H:i:s') ], ['id' => $user["id"]]);
            $this->session->set('login_data', $sess_data);
            $return['status'] = true;
            $return['message'] = "User Verified ! Redirecting you to the dashboard";
        } else {
            $return['status'] = false;
            $return['message'] = "Invalid OTP !";
        }
        echo json_encode($return);
    }
    function getMenuSlugsByIDs($menuIDs) {
        $where = "id IN (" . $menuIDs . ") AND status='Active'";
        $slugs = db()->table('dt_menus')->select('slug')->where($where)->get()->getResultArray();
        return $slugs;
    }
    
    public function logout() {
        $session = \Config\Services::session();
        $email = $session->get('login_data')['role_user_email'];
        $this->c_model->updateRecords('users', ['otp_sent' => null], ['user_email' => $email]);
        addUserLog('Logout','Logout');
        $session->destroy();
        return redirect()->to(base_url());
    }
    
    function forgot_password() {
        $data["meta_title"] = "Forgot Password";
        echo view("admin/forgot-password", $data);
    }
    
    public function sendNewPassword() {
        $email = !empty($this->request->getVar('email')) ? trim($this->request->getVar('email')) : "";
        if (empty($email)) {
            echo "Please Enter Email";
            exit;
        }
        $valid = $this->c_model->getSingle("users", 'id', ['user_email' => $email]);
        if (empty($valid)) {
            echo "This Email Id Is Not Registered";
            exit;
        }
        addUserLog('Forgot Password',"You're requested a new password");
        $password = generate_password(10);
        sendEmailForgotPassword($email, $email, $password);
        $this->c_model->updateRecords("users", ['enc_password' => md5($password), 'raw_password' => $password], ['user_email' => $email]);
        echo "success";
    }
    
    public function getBrowserName() {
        $data = [];
        $agent = $this->request->getUserAgent();
        $isMob = is_numeric(strpos(strtolower($agent), "mobile"));
        $isTab = is_numeric(strpos(strtolower($agent), "tablet"));
        $isWin = is_numeric(strpos(strtolower($agent), "windows"));
        $isAndroid = is_numeric(strpos(strtolower($agent), "android"));
        $isIPhone = is_numeric(strpos(strtolower($agent), "iphone"));
        $isIPad = is_numeric(strpos(strtolower($agent), "ipad"));
        $isIOS = $isIPhone || $isIPad;
        if ($isMob) {
            if ($isTab) {
                $data['device'] = 'Tablet';
            } else {
                $data['device'] = 'Mobile';
            }
        } else {
            $data['device'] = 'Desktop';
        }
        if ($isAndroid) {
            $data['os'] = 'Android';
        } else if ($isWin) {
            $data['os'] = 'Windows';
        } else {
            $data['os'] = 'iOS';
        }
        return $data;
    }
    
    protected function addLoginActivity($userId, $userName, $ipAddress, $deviceInfo) {
        if ($ipAddress != '::1') {
            $ipDetails = file_get_contents("http://ip-api.com/json/" . $ipAddress);
        } else {
            $ipDetails = file_get_contents("http://ip-api.com/json/");
        }
        $ipLog = json_decode($ipDetails, true);
        $saveLog = [];
        $saveLog['user_id'] = $userId;
        $saveLog['user_name'] = $userName;
        $saveLog['login_at'] = date('Y-m-d H:i:s');
        $saveLog['os'] = !empty($deviceInfo['os']) ? $deviceInfo['os'] : '';
        $saveLog['device'] = !empty($deviceInfo['device']) ? $deviceInfo['device'] : '';
        $saveLog['login_city'] = !empty($ipLog['city']) ? $ipLog['city'] : '';
        $saveLog['login_state'] = !empty($ipLog['regionName']) ? $ipLog['regionName'] : '';
        $saveLog['login_country'] = !empty($ipLog['country']) ? $ipLog['country'] : '';
        $saveLog['login_ip'] = $ipAddress;
        $saveLog['add_date'] = date('Y-m-d H:i:s');
        $activity_id = $this->c_model->insertRecords('dt_activity_log', $saveLog);
        $saveLog['activity_id'] = $activity_id;
        return $saveLog;
    }
}
?>
