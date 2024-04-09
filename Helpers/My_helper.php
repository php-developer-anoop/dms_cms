<?php
if (!function_exists('db')) {
    function db() {
        $db = \Config\Database::connect();
        return $db;
    }
}
if (!function_exists('adminview')) {
    function adminview($pagename, $data) {
        $session = session()->get('login_data');
        $html = '';
        if ($session) {
            $status = getProfileStatus($session['role_user_email']);
            if (!empty($status) && $status !== "Active") {
                session()->destroy();
                $html.= '
                <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;height: 90%;">
                <p style="color:red;font-size:32px;text-align:center;margin:0 0 10px 0">Oops !</p>
                <b style="font-size:28px">Your access has been disabled from ADMIN <br> Please contact ADMIN for more information</b>
                </div>';
                echo $html;
                return redirect()->to(base_url(ADMINPATH . "login"));
            }
        } else {
            return redirect()->to(base_url(ADMINPATH . "login"));
        }
        $company = websetting('*');
        $data['company'] = $company;
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        $data['logo'] = !empty($company['logo']) ? base_url('uploads/') . $company['logo'] : "";
        echo view(ADMINPATH . "includes/meta_file", $data);
        echo view(ADMINPATH . "includes/all_css", $data);
        echo view(ADMINPATH . "includes/header", $data);
        echo view(ADMINPATH . "includes/sidebar", $data);
        echo view(ADMINPATH . $pagename, $data);
        echo view(ADMINPATH . "includes/all_js", $data);
        echo view(ADMINPATH . "includes/footer", $data);
    }
}
if (!function_exists("frontview")) {
    function frontview($page_name, $data) {
        $company = websetting('*');
        $data['company'] = $company;
        $data['favicon'] = !empty($company['favicon']) ? base_url('uploads/') . $company['favicon'] : "";
        $data['logo'] = !empty($company['logo']) ? base_url('uploads/') . $company['logo'] : "";
        echo view("frontend/includes/meta_file", $data);
        echo view("frontend/includes/all_css", $data);
        echo view("frontend/includes/header", $data);
        echo view("frontend/" . $page_name, $data);
        echo view("frontend/includes/footer", $data);
        echo view("frontend/includes/all_js", $data);
    }
}
if (!function_exists("websetting")) {
    function websetting($select) {
        $company = db()->table('dt_websetting')->select($select)->get()->getRowArray();
        return $company;
    }
}
if (!function_exists("validate_slug")) {
    function validate_slug($text, string $divider = '-') {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}
if (!function_exists('convertImageInToWebp')) {
    function convertImageInToWebp($folderPath, $uploaded_file_name, $new_webp_file) {
        $source = $folderPath . '/' . $uploaded_file_name;
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $quality = 100;
        $image = '';
        if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($extension == 'gif') {
            $image = imagecreatefromgif($source);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
        } else {
            $image = $uploaded_file_name;
        }
        $destination = $folderPath . '/' . $new_webp_file;
        $webp_upload_done = imagewebp($image, $destination, $quality);
        return $webp_upload_done ? $new_webp_file : '';
    }
}
if (!function_exists('count_data')) {
    function count_data($column, $table, $where = null) {
        $builder = db()->table($table);
        if (!empty($where)) {
            $builder->where($where);
        }
        $count = $builder->countAllResults($column);
        return $count;
    }
}
function random_alphanumeric_string($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $length);
}
function generate_password($length) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$';
    return substr(str_shuffle($chars), 0, $length);
}
function FetchExactBrowserName() {
    $userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    if (strpos($userAgent, "opr/") !== false) {
        return "Opera";
    } elseif (strpos($userAgent, "chrome/") !== false) {
        return "Chrome";
    } elseif (strpos($userAgent, "msie") !== false || strpos($userAgent, "trident/") !== false) {
        return "Internet Explorer";
    } elseif (strpos($userAgent, "firefox/") !== false) {
        return "Firefox";
    } elseif (strpos($userAgent, "safari/") !== false) {
        return "Safari";
    } else {
        return "OUT OF DATA";
    }
}
function imgExtension($image_jpg_png_gif, $image_webp = null) {
    $browserName = FetchExactBrowserName();
    if (in_array($browserName, ["chrome"]) && !empty($image_webp)) {
        /*for webp image*/
        return $image_webp; /*you can add image folder path like base_url('<folder name>/'.$image_webp)*/
    } else {
        return $image_jpg_png_gif; /*you can add image folder path like base_url('<folder name>/'.$image_jpg_png_gif)*/
    }
}
if (!function_exists('getTimeInterval')) {
    function getTimeInterval($time) {
        $current_time = new DateTime();
        $from = new DateTime($time);
        $difference = $current_time->diff($from);
        // Calculate the total hours (including days)
        $totalHours = $difference->days * 24 + $difference->h;
        return $totalHours;
    }
}
function getData($table, $keys = null, $where = null, $limit = null, $offset = null, $order_by = null) {
    $builder = db()->table($table);
    if (!empty($keys)) {
        $builder->select($keys);
    }
    if (!empty($where)) {
        $builder->where($where);
    }
    if (!empty($limit) && !empty($offset)) {
        $builder->limit($limit, $offset);
    } elseif (!empty($limit) && empty($offset)) {
        $builder->limit($limit);
    }
    if (!empty($order_by)) {
        $builder->orderBy($order_by);
    }
    return $builder->get()->getResultArray();
}
function getSingle($table, $keys = null, $where = null, $limit = null, $offset = null, $order_by = null) {
    $builder = db()->table($table);
    if (!empty($keys)) {
        $builder->select($keys);
    }
    if (!empty($where)) {
        $builder->where($where);
    }
    if (!empty($limit) && !empty($offset)) {
        $builder->limit($limit, $offset);
    } elseif (!empty($limit) && empty($offset)) {
        $builder->limit($limit);
    }
    if (!empty($orderby)) {
        $builder->orderBy($orderby);
    }
    return $builder->get()->getRowArray();
}
function insertRecords($table, $data) {
    $builder = db()->table($table);
    $builder->insert($data);
    return db()->insertID();
}
function showRatings($ratingValue) {
    //$emptyStar = '<li><i class="bi bi-star-fill"></i></li>'; // ★
    $filledStar = '<li><i class="bi bi-star-fill"></i></li>'; // ☆
    $filledStarsCount = $ratingValue;
    $emptyStarsCount = 5 - $ratingValue;
    $ratingsHTML = '';
    for ($i = 0;$i < $filledStarsCount;$i++) {
        $ratingsHTML.= $filledStar;
    }
    // for ($i = 0; $i < $emptyStarsCount; $i++) {
    //     $ratingsHTML .= $emptyStar;
    // }
    return $ratingsHTML;
}
if (!function_exists('getSubMenuList')) {
    function getSubMenuList($menuList, $parent_menu_id) {
        return array_filter($menuList, function ($item) use ($parent_menu_id) {
            return $item['menu_id'] === $parent_menu_id;
        });
    }
}
if (!function_exists('checkWriteMenus')) {
    function checkWriteMenus($uri) {
        $session = session()->get('login_data');
        $write_slug = !empty($session['write_slug']) ? $session['write_slug'] : [];
        return !empty($uri) && in_array($uri, $write_slug);
    }
}
if (!function_exists('getDefinedSlugs')) {
    function getDefinedSlugs($menuIDs) {
        $where = "id IN (" . $menuIDs . ")";
        $slugs = db()->table('dt_menus')->select('slug')->where($where)->get()->getResultArray();
        return $slugs;
    }
}
if (!function_exists('updateUserSession')) {
    function updateUserSession() {
        $user = getSingle("users", 'read_menu_ids,write_menu_ids', ['user_email' => session()->get('login_data') ['role_user_email']]);
        $readSlugs = getDefinedSlugs($user['read_menu_ids']);
        $writeSlugs = getDefinedSlugs($user['write_menu_ids']);
        $sess_data = session()->get('login_data');
        $sess_data['read_menu_ids'] = $user['read_menu_ids'];
        $sess_data['write_menu_ids'] = $user['write_menu_ids'];
        $sess_data['read_slug'] = $readSlugs;
        $sess_data['write_slug'] = $writeSlugs;
        session()->set('login_data', $sess_data);
    }
}
if (!function_exists('getUri')) {
    function getUri($segment) {
        $uri = service('uri');
        $url = $uri->getSegment($segment);
        return $url;
    }
}
if (!function_exists('getFormatFinancialYear')) {
    function getFormatFinancialYear($start_year, $end_year = null) {
        //Calculate Financial Year date
        $data = [];
        //$current_date = date('Y-m-d');
        $current_date = $start_year;
        $start_financial_year = date('Y', strtotime($current_date)) . FINANCIAL_YEAR_START;
        if (strtotime($current_date) > strtotime($start_financial_year)) {
            $start_year = date('Y', strtotime($current_date)) . FINANCIAL_YEAR_START;
            $end_year = date('Y', strtotime($current_date . ' +1 year ')) . FINANCIAL_YEAR_END;
        } else if (strtotime($current_date) < strtotime($start_financial_year)) {
            $start_year = date('Y', strtotime($current_date . ' -1 year ')) . FINANCIAL_YEAR_START;
            $end_year = date('Y', strtotime($current_date)) . FINANCIAL_YEAR_END;
        }
        $data['start_year'] = $start_year;
        $data['end_year'] = $end_year;
        return $data;
    }
}
if (!function_exists('getTeacherName')) {
    function getTeacherName($id) {
        $teacher = getSingle("teacher_master", 'teacher_name,', ['status' => 'Active', 'id' => $id]);
        return $teacher['teacher_name'];
    }
}
if (!function_exists('getTeacherNameCmsCode')) {
    function getTeacherNameCmsCode($id) {
        $teacher = getSingle("teacher_master", 'teacher_name,cmss_code', ['status' => 'Active', 'id' => $id]);
        return $teacher['teacher_name'] . '/' . $teacher['cmss_code'];
    }
}
if (!function_exists('getProfileStatus')) {
    function getProfileStatus($user_email) {
        $teacher = getSingle("users", 'status', ['user_email' => $user_email]);
        return $teacher['status'];
    }
}
if (!function_exists('getUserLog')) {
    function addUserLog($activity_type, $details) {
    $sessionData = session()->get('role_user') ?? session()->get('login_data');

    if (isset($sessionData)) {
        $user_info = getSingle('users', 'user_type,id,user_name', ['user_email' => $sessionData['role_user_email']]);
        if ($user_info) {
            $data = [
                'user_id'      => ($user_info['user_type'] == "Admin") ? $user_info['id'] : null,
                'user_name'    => ($user_info['user_type'] == "Admin") ? $user_info['user_name'] : null,
                'activity_type'=> $activity_type,
                'details'      => $details,
                'add_date'     => date('Y-m-d H:i:s'),  // Use your specific method to get current timestamp
            ];

            try {
                $id = insertRecords('visit_activity_log', $data);
                return $id;
            } catch (Exception $e) {
                // Log or handle the exception
                return false;
            }
        }
    }

    // Handle the case where session data or user info is not available
    return false;
}

    function getUserEmail($id){
        $email = getSingle('users', 'user_email', ['id' => $id]);
        return !empty($email['user_email']) ?  $email['user_email'] : '';
    }

}
