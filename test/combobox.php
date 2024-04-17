<?php

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    require_once ('site_variables.php');
    $GLOBALS['WEBSITE_VARS'] = true;
}
if(!isset($GLOBALS['CONNECTION_VARS'])) {
    require_once ('database/connection.php');
    $GLOBALS['CONNECTION_VARS'] = true;
}

