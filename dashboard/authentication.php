<?php
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}

    use classes\user;
///////////////////////////////////////////////////////
global $user_obj;
if(!empty($user_obj)) {
    $user_obj = null;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['logoutButton'])) {
        unset($_POST['logoutButton']);
        $user_obj = unserialize($_SESSION['USER_OBJ']);
        if($user_obj instanceof user) {
            try {
                CONNECTION->logout($user_obj);
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
        deleteSessionCookies();
        header('Location: ' . relativePath(ABSOLUTE_PATHS['HOME_PAGE']));
    }
}

if(isset($_COOKIE['USER_TOKEN']) || isset($_SESSION['USER_OBJ'])) {
    // problem while searching entering the conditional at any case
    $user_obj = empty($_SESSION['USER_OBJ']) ? null : unserialize($_SESSION['USER_OBJ']);
    if(!($user_obj instanceof user && $user_obj->session_id === $_COOKIE['USER_TOKEN'])) {
        deleteSessionCookies();
        header('Location: ' . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
    }
}
else {
    deleteSessionCookies();
    header("Location: " . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
}
