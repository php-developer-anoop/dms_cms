<?php
use CodeIgniter\Router\RouteCollection;
$routes->get('/', 'Home::index');
$routes->get(ADMINPATH . "login", "Authentication::index", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "otp-verification", "Authentication::otp_verification", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "forgot-password", "Authentication::forgot_password", ["namespace" => "App\Controllers\Admin"]);
$routes->post(ADMINPATH . "sendNewPassword", "Authentication::sendNewPassword", ["namespace" => "App\Controllers\Admin"]);
$routes->post(ADMINPATH . "verifyOtp", "Authentication::verifyOtp", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "logout", "Authentication::logout", ["namespace" => "App\Controllers\Admin"]);
$routes->get(ADMINPATH . "dashboard", "Dashboard::index", ["filter" => "auth", "namespace" => "App\Controllers\Admin"]);
$routes->match(["get", "post"], ADMINPATH . "authenticate", "Authentication::authenticate", ["namespace" => "App\Controllers\Admin"]);
$routes->group("admin", ["filter" => 'auth', "namespace" => "App\Controllers\Admin"], function ($routes) {
    $routes->match(["get", "post"], "deleteRecords", "Ajax::index");
    $routes->match(["get", "post"], "getSlug", "Ajax::getSlug");
    $routes->match(["get", "post"], "changeStatus", "Ajax::changeStatus");
    $routes->match(["get", "post"], "assign-menus", "Ajax::assign_menus");
    $routes->match(["get", "post"], "getCount", "Ajax::getCount");
    $routes->match(["get", "post"], "getTeacherName", "Ajax::getTeacherName");
    $routes->match(["get", "post"], "getRecords", "Ajax::getRecords");
    $routes->match(["get", "post"], "save_record", "Ajax::save_record");
    $routes->match(["get", "post"], "getTeacherDetail", "Ajax::getTeacherDetail");
    $routes->match(["get", "post"], "checkDuplicateUser", "Ajax::checkDuplicateUser");
    $routes->match(["get", "post"], "checkDuplicateSchool", "Ajax::checkDuplicateSchool");
    $routes->match(["get", "post"], "checkDuplicateTeacher", "Ajax::checkDuplicateTeacher");
    $routes->match(["get", "post"], "assignmenu", "Ajax::assignmenu");
    $routes->match(["get", "post"], "getTeachersList", "Ajax::getTeachersList");
    $routes->match(["get", "post"], "checkDuplicateYear", "Ajax::checkDuplicateYear");
    $routes->match(["get", "post"], "add-user-log", "Ajax::add_user_log");
    $routes->match(["get", "post"], "addDownloadActivity", "Ajax::addDownloadActivity");
    // Web Setting
    $routes->match(["get", "post"], "save-setting", "Websetting::save_setting");
    $routes->match(["get", "post"], "websetting", "Websetting::index");
    // Menu Master
    $routes->match(["get", "post"], "save-menu", "Menu::save_menu");
    $routes->match(["get", "post"], "menu-list", "Menu::index");
    $routes->match(["get", "post"], "add-menu", "Menu::add_menu");
    $routes->match(["get", "post"], "menu-data", "Menu::getRecords");
    $routes->match(["get", "post"], "assign-menu", "Menu::assign_menu");
    //Activity Log
    $routes->match(["get", "post"], "activity-log", "Activity_log::index");
    $routes->match(["get", "post"], "log-data", "Activity_log::getRecords");
    // User Activity Log
    $routes->match(["get", "post"], "user-activity-log", "Activity_log::user_activity_log");
    $routes->match(["get", "post"], "user-activity-log-data", "Activity_log::getUserActivityRecords");
    // User Master
    $routes->match(["get", "post"], "save-user", "User::save_user");
    $routes->match(["get", "post"], "user-list", "User::index");
    $routes->match(["get", "post"], "add-user", "User::add_user");
    $routes->match(["get", "post"], "user-data", "User::getRecords");
    $routes->match(["get", "post"], "change-password", "User::change_password");
    $routes->match(["get", "post"], "update-password", "User::update_password");
    // School Master
    $routes->match(["get", "post"], "save-school", "School::save_school");
    $routes->match(["get", "post"], "school-list", "School::index");
    $routes->match(["get", "post"], "add-school", "School::add_school");
    $routes->match(["get", "post"], "school-data", "School::getRecords");
    // Financial Year Master
    $routes->match(["get", "post"], "save-year", "Financial_year::save_year");
    $routes->match(["get", "post"], "years-list", "Financial_year::index");
    $routes->match(["get", "post"], "add-year", "Financial_year::add_year");
    $routes->match(["get", "post"], "year-data", "Financial_year::getRecords");
    // Teacher Master
    $routes->match(["get", "post"], "save-teacher", "Teacher::save_teacher");
    $routes->match(["get", "post"], "employee-list", "Teacher::index");
    $routes->match(["get", "post"], "add-employee", "Teacher::add_teacher");
    $routes->match(["get", "post"], "teacher-data", "Teacher::getRecords");
    $routes->match(["get", "post"], "view-teacher-detail", "Teacher::view_teacher_detail");
    $routes->match(["get", "post"], "save-master-file", "Teacher::save_master_file");
    // Records Master
    $routes->match(["get", "post"], "save-record", "Records::save_record");
    $routes->match(["get", "post"], "records-list", "Records::index");
    $routes->match(["get", "post"], "add-records", "Records::add_record");
    $routes->match(["get", "post"], "record-data", "Records::getRecords");
    // Grade Master
    $routes->match(["get", "post"], "save-grade", "Grade::save_grade");
    $routes->match(["get", "post"], "grades-list", "Grade::index");
    $routes->match(["get", "post"], "add-grade", "Grade::add_grade");
    $routes->match(["get", "post"], "grade-data", "Grade::getRecords");
});
