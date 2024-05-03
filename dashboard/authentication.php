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

// Login validity
if(isset($_COOKIE['USER_TOKEN']) || isset($_SESSION['USER_OBJ'])) {
    $user_obj = empty($_SESSION['USER_OBJ']) ? null : unserialize($_SESSION['USER_OBJ']);
    if(!($user_obj instanceof user && $user_obj->session_id === $_COOKIE['USER_TOKEN'])) {
        deleteSessionCookies();
        header('Location: ' . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
    }
    else {
        // valid login
    }
}
else {
    $user_obj = null;
    header('Location: ' . relativePath(ABSOLUTE_PATHS['HOME_PAGE']));
}
