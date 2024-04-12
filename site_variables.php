<?php

const RELATIVE_ROOT = "/github";

$relative_root = RELATIVE_ROOT;
$boostrap_include = <<< ENDL_
<link rel="stylesheet" href="{$relative_root}/precompiled/simplex/bootstrap-color.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="{$relative_root}/styles/styles.css">
<script src="{$relative_root}/scripts/main.js"></script>
ENDL_;

define('ABSOLUTE_PATHS', array(
    "HOME_PAGE"                     => $_SERVER['DOCUMENT_ROOT'] . '/index.php',
    "MENU_PAGE"                     => $_SERVER['DOCUMENT_ROOT'] . '/home_components/menu.php',
    "ARTICLES_PAGE"                 => $_SERVER['DOCUMENT_ROOT'] . '/home_components/articles.php',
    "FOOTER_PAGE"                   => $_SERVER['DOCUMENT_ROOT'] . '/home_components/footer.php',
    "CUSTOMER_REGISTRATION_FORM"    => $_SERVER['DOCUMENT_ROOT'] . '/forms/registration.php',
    "BUSINESS_REGISTRATION_FORM"    => $_SERVER['DOCUMENT_ROOT'] . '/forms/registrationBusiness.php',
    "LOGIN_PAGE"                    => $_SERVER['DOCUMENT_ROOT'] . '/forms/login.php',
    "LOADING_PAGE"                  => $_SERVER['DOCUMENT_ROOT'] . '/home_components/loading.php',
    "SUCCESSFUL_REGISTRATION"       => $_SERVER['DOCUMENT_ROOT'] . '/forms/successful_registration.php',
    "LOCAL_STYLESHEET"              => $_SERVER['DOCUMENT_ROOT'] . '/styles/styles.css',
    "FULL_BOOTSTRAP"                => $boostrap_include
));

if(!file_exists(ABSOLUTE_PATHS['HOME_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['MENU_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['ARTICLES_PAGE'])) die("Articles page file not found.");
if(!file_exists(ABSOLUTE_PATHS['FOOTER_PAGE'])) die("Footer file not found.");
if(!file_exists(ABSOLUTE_PATHS['LOCAL_STYLESHEET'])) die("Stylesheet file not found.");

function relativePath($absolutePath, $separator = DIRECTORY_SEPARATOR) : string {
    $a = explode($separator, $absolutePath);
    $b = explode($separator, $_SERVER['REQUEST_URI']);
    $fileName = end($a);

    array_splice($a, 0, 1);                                  // Remove first empty element
    array_splice($b, 0, 1);                                  // Remove first empty element

    $b = array_slice($b, 0, count($b) - 1);                         // Remove last element from URI representing the webpage file

    if($a === $_SERVER['DOCUMENT_ROOT']) return $fileName;                      // The given path and the document root are the same, the relative path is the filename
    if(file_exists($absolutePath)) $a = array_slice($a, 0, count($a) - 1);     // If absolutePath is a file, remove filename from the path to compare directories
    else if(!is_dir($absolutePath)) return "";                                  // The absolute path given is nor a file's address, nor a correct directory path

    if(count($b) == 0) {                                                        // The request URI is from the first page, so there are no subfolders
        $c = explode($separator, $_SERVER['DOCUMENT_ROOT']);
        array_splice($c, 0, 1);                               // Remove first empty element
        $index = 0;

        for($i = 0; $i < count($a) && $i < count($c); $i++) {
            if($a[$i] === $c[$i]) {                                              // Compare the given path with the URI to remove common folders from the relative path
                $index = $i;
            }
            else break;
        }

        $a = array_slice($a, $index);                                           // Remove the common directories from the absolute path
        return implode($separator, $a) . '/' . $fileName;                       // Return the relative path
    }

    for($i = 0; $i < count($a) && count($b) > 0; $i++) {                        // The request URI has subfolders
        if($b[0] === $a[$i]) {                                                  // Compare root directories of the URI with given path
            $a = array_slice($a, $i);                                           // Exclude common directories path found in the URI
            break;
        }
    }

    if($a === $b) return $fileName;
    // Calculate super or sub folder relative path from a same common level directory
    $len1 = count($a);
    $len2 = count($b);
    $index = 0;
    $out = "";
    for($i = 0; $i < $len1 && $i < $len2; $i++) {
        if($a[$i] != $b[$i]) {
            $out .= "../";                                                      // Add parent link for different level directories
        }
        else {
            $index = $i;                                                        // Last level where the paths are similar
        }
    }
    $out .= implode($separator, array_slice($a, $index + 1));
    $out .= '/' . $fileName;
    return $out;
}