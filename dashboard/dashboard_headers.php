<?php

use classes\user;

(include (relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_AUTH']))) or die("Authentication file not found");    // Needed to check credentials

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Logout button of the dashboard menu
    if(isset($_POST['logoutButton'])) {
        unset($_POST['logoutButton']);
        $user_obj = empty($_SESSION['USER_OBJ']) ? null : unserialize($_SESSION['USER_OBJ']);
        if($user_obj instanceof user) {
            try {
                CONNECTION->logout($user_obj);
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
        deleteSessionCookies();
        header('Location: ' . relativePathSystem(ABSOLUTE_PATHS['LOGIN_PAGE']));
    }

    // Dashboard sidebar form
    if(isset($_POST['theme'])) {
        $val = (int)$_POST['theme'];
        if($val >= 0 && $val <= 40) {
            if(!empty($user_obj) && $user_obj instanceof user) {
                CONNECTION->setTheme($user_obj->id, USER_THEMES[$val]);
            }
            $GLOBALS['USER_THEME'] = USER_THEMES[$val];
            // try to refresh with headers
        }
    }
}
