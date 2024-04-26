<?php
header('Content-Type: application/json');
if (!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once($_SERVER['DOCUMENT_ROOT'] . '/site_variables.php')) or die("Variables file not found");
    $GLOBALS['WEBSITE_VARS'] = true;
}
if (!isset($GLOBALS['CONNECTION_VARS'])) {
    (require_once(relativePath(ABSOLUTE_PATHS['CONNECTION']))) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}

$countryCode = filter_var($_GET['countryCode']);    // null on vales different from boolean

if($countryCode !== null && strtolower($_GET['countryCode']) === 'all') {
    echo json_encode(CONNECTION->getCountries());
}
else if($countryCode !== null && strtolower($_GET['countryCode']) !== 'all') {
    echo json_encode(CONNECTION->getCities($_GET['countryCode']));
    /*$json = '[';
    foreach($x as $row) {
        $json .= "{\"id\": \"" . $row["id"] . "\",";
        $json .= "\"name\": \"" . $row["name"] . "\"},";
    }
    $json = substr($json, 0, -1);
    $json .= ']';
    echo $json;*/
}
