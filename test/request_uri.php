<?php

(require_once '../site_variables.php') or die("Site vars not found");
function getURI() {
    $ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || $_SERVER['SERVER_PORT'] == 433);
    $out = $ssl ? "https://" : "http://";
    $out .= $_SERVER['HTTP_HOST'];
    $out .= $_SERVER['REQUEST_URI'];
    // https are null because a configuration in httpd
    return $out;
}

//echo "<h1>" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</h1>";
echo <<< ENDL
<style>body {background-color: gray;}</style>
ENDL;


