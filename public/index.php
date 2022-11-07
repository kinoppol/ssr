<?php
ob_start();
session_start();
define('SYSTEMNAME','ระบบบันทึกคะแนนการเรียนการสอนหลักสูตรวิชาชีพระยะสั้น');
define('VERSION','21.05.10');
ini_set('memory_limit', '512M');
$_SESSION['FOOTSCRIPT']='';
$_SESSION['FOOTSYSTEM']='';
$_SESSION['LIB']=array();
// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load our paths config file
// This is the line that might need to be changed, depending on your folder structure.
require realpath(FCPATH . '../app/Config/Paths.php') ?: FCPATH . '../app/Config/Paths.php';
// ^^^ Change this if you move your application folder

$paths = new Config\Paths();

// Location of the framework bootstrap file.
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app       = require realpath($bootstrap) ?: $bootstrap;

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */
$allowNoneLoginUser=array(
    '/user/login/board',
    '/user/login/boc',
    '/user/login/gov',
    '/user/login/institute',
    '/user/login/school',
    '/user/loginSelector',
    '/user/checkLogin',
    '/user/checkGoogle',
    '/user/registerNewUser',
    '/user/checkSignUp',
    '/user/forgetPassword',
);
if(empty($_COOKIE['current_user'])) {
    if(!empty($_SERVER['PATH_INFO'])){
        if(!is_numeric(array_search($_SERVER['PATH_INFO'],$allowNoneLoginUser))){
            //print_r($_SERVER);
            print '<meta http-equiv="refresh" content="0;url='.site_url('public/user/loginSelector').'">';
            exit();
        }
    }else{        
        print '<meta http-equiv="refresh" content="0;url='.site_url('landing').'">';
    }
}
$app->run();

