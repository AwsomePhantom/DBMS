<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/site_variables.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/connection.php');

for($i = 0; $i < 10; $i++) {
    echo CONNECTION->generateID() . "<br>";
}
