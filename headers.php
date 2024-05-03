<?php

use classes\user;

(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_AUTH'])) or die("Connection related file not found");   // Check for user credentials

if(isset($_COOKIE['USER_TOKEN']) && isset($_SESSION['USER_OBJ'])) {
    $user_obj = unserialize($_SESSION['USER_OBJ']);
    if(!empty($user_obj) && $user_obj instanceof user && $user_obj->session_id === $_COOKIE['USER_TOKEN']) {
        header("Location: " . relativePathSystem(ABSOLUTE_PATHS['DASHBOARD']));
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Login form of the modal window inside the mainMenu.php
    if(isset($_POST['loginUsernameField']) && isset($_POST['loginPasswordField'])) {
        try {
            $user_obj = CONNECTION->login($_POST['loginUsernameField'], $_POST['loginPasswordField']);
            if($user_obj !== null) {
                $_SESSION['USER_OBJ'] = serialize($user_obj);
                setcookie('USER_TOKEN', (string)$user_obj->session_id, time() + (3600 * 24), '/');
                $theme = CONNECTION->getTheme($user_obj);
                if($theme) {
                    $GLOBALS['USER_THEME'] = $theme;
                }
                header("Location: " . relativePathSystem(ABSOLUTE_PATHS['DASHBOARD']));
            }
            else {
                global $errorMsg;
                $errorMsg = "Login attempt: Invalid username or password";
                unset($_POST['loginUsernameField']);
                unset($_POST['loginPasswordField']);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}