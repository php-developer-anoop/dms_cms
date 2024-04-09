<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class Auth implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        $currentTime = time();
        $logoutTime = strtotime('00:00:00');
        if ($currentTime == $logoutTime) {
            session()->destroy();
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        if (!session()->get("login_data")) {
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        $session = session()->get('login_data');
        if ($session['role'] == "Admin") {
            $uri = $request->uri->getSegment(2);
            if (($this->checkReadAccess($uri, $session['read_slug'], $session['allmenu']) == false)) {
                session()->setFlashdata('failed', 'Access Denied  !!');
                return redirect()->to(base_url(ADMINPATH . 'dashboard'));
            }
        }
    }
    public function checkReadAccess($uri, $readPermissions, $all_menu) {
        if (in_array($uri, $all_menu)) {
            if (!in_array($uri, $readPermissions)) {
                return false; // User does not have read access
            } else {
                return true; // User has read access
            }
        }
        return true; // User has read access
    }
    public function checkWriteAccess($uri, $writePermissions, $all_menu) {
        if (in_array($uri, $all_menu)) {
            if (!in_array($uri, $writePermissions)) {
                return false; // User does not have write access
            }
        }
        return true; // User has write access
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    }
}
