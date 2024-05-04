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

    if(isset($_POST['emergencyTitle']) &&
        isset($_POST['emergencyAddress']) &&
        isset($_POST['emergencyMessage'])) {
        try {
            global $errorMsg;
            $jsonArray = json_decode(file_get_contents("https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$_POST['gpsx']}&longitude={$_POST['gpsy']}"), true);
            if(empty($jsonArray)) {
                $errorMsg = 'Failed to retrieve data';
                return;
            }
            $country_code = CONNECTION->getCountryCode($jsonArray['countryName']);
            $res = CONNECTION->reportIncident($user_obj->id, $_POST['emergencyTitle'], $country_code, $jsonArray['city'], $_POST['emergencyAddress'], $_POST['emergencyMessage'], $jsonArray['latitude'], $jsonArray['longitude']);
            if(!$res) {
                $errorMsg = 'Failed to report incident, please try again';
            }
            unset($_POST['emergencyTitle']);
            unset($_POST['emergencyAddress']);
            unset($_POST['emergencyMessage']);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    // Dashboard sidebar form
    if(isset($_POST['theme'])) {
        $val = (int)$_POST['theme'];
        if($val >= 0 && $val <= 40) {
            if(!empty($user_obj) && $user_obj instanceof user) {
                try {
                    CONNECTION->setTheme($user_obj->id, USER_THEMES[$val]);
                }
                catch (Exception $e) {
                    throw new Exception($e->getMessage(), $e->getCode());
                }
            }
            $GLOBALS['USER_THEME'] = USER_THEMES[$val];
            // try to refresh with headers
        }
    }
}
