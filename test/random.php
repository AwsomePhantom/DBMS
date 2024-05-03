<?php
session_start();

if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
//////////////////////////////////////////////////////////////////////////

    echo uniqid('user_', true) . '<br>';

    $bytes = openssl_random_pseudo_bytes(16);
    echo strtoupper(bin2hex($bytes))  . '<br>';

    $unique_id = md5(uniqid(mt_rand(), true));
    echo strtoupper($unique_id)  . '<br>';

    if(isset($_SERVER['REQUEST_METHOD']) == 'POST') {
        var_dump($_POST);
    }
