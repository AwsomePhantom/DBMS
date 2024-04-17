<?php

const ROOT_DIR = __DIR__;
$relative_root = relativePath(ROOT_DIR);

const USER_THEMES = array(
    // 10 PER ROW
    'abaculus', 'backstay', 'bubblegum', 'business-tycoon', 'cable', 'cerulean', 'charming', 'cosmo', 'cyborg', 'dalton',
    'darkly', 'daydream', 'ectype', 'executive-suite', 'ferula', 'flatly', 'good-news', 'growth', 'harbor', 'hello-world',
    'journal', 'litera', 'lumen', 'lux', 'materia', 'minty', 'neon-glow', 'pleasant', 'pulse', 'retro',
    'sandstone', 'simplex', 'sketchy', 'slate', 'solar', 'spacelab', 'superhero', 'united', 'vibrant-sea', 'wizardry',
    'yeti');

$GLOBALS['USER_THEME'] = USER_THEMES[31];                           // Global user theme name form array

const ABSOLUTE_PATHS = array(
    "HOME_PAGE"                     => ROOT_DIR . '/index.php',
    "MENU_PAGE"                     => ROOT_DIR . '/home_components/menu.php',
    "ARTICLES_PAGE"                 => ROOT_DIR . '/home_components/articles.php',
    "FOOTER_PAGE"                   => ROOT_DIR . '/home_components/footer.php',
    "CUSTOMER_REGISTRATION_FORM"    => ROOT_DIR . '/forms/registration.php',
    "BUSINESS_REGISTRATION_FORM"    => ROOT_DIR . '/forms/registrationBusiness.php',
    "LOGIN_PAGE"                    => ROOT_DIR . '/forms/login.php',
    "LOADING_PAGE"                  => ROOT_DIR . '/home_components/loading.php',
    "SUCCESSFUL_REGISTRATION"       => ROOT_DIR . '/forms/successful_registration.php',
    "LOCAL_STYLESHEET"              => ROOT_DIR . '/styles/styles.css',
    "LOCAL_SCRIPTS"                 => ROOT_DIR . '/scripts/main.js',
    "CONNECTION"                    => ROOT_DIR . '/database/connection.php'
);

if(!file_exists(ABSOLUTE_PATHS['HOME_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['MENU_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['ARTICLES_PAGE'])) die("Articles page file not found.");
if(!file_exists(ABSOLUTE_PATHS['FOOTER_PAGE'])) die("Footer file not found.");
if(!file_exists(ABSOLUTE_PATHS['LOCAL_STYLESHEET'])) die("Stylesheet file not found.");

function relativePath($absolutePath, $separator = DIRECTORY_SEPARATOR) : string {
    if($absolutePath === ROOT_DIR) return '';

    $path = explode($separator, $absolutePath);
    $uri = explode($separator, $_SERVER['REQUEST_URI']);
    $root = explode($separator, ROOT_DIR);
    $fileName = null;

    array_splice($path, 0, 1);      // remove first empty element
    array_splice($uri, 0, 1);       // remove first empty element
    array_splice($root, 0, 1);      // remove first empty element
    array_pop($uri);                            // remove index.* file name from the URI
    while(count($path) > 0 && end($path) == '') {   // remove empty elements in the tail of the path
        array_pop($path);
    }

    if(!is_file($absolutePath) && !is_dir($absolutePath)) return '[INVALID PATH]'; // invalid path
    if(is_file($absolutePath)) {
        $fileName = end($path);
        array_pop($path);
    }

    // Cut until ROOT_DIR of the website
    $index = -1;            // computer folders and absolute paths have always common prefix
    for($i = 0; $i < count($path); $i++) {
        if($root[$i] !== $path[$i]) {
            $index = $i;
            break;
        }
    }
    array_splice($path, 0, $i - 1);     // URI and Path at the same level
    if(serialize($path) === serialize($uri)) return $fileName;      // Same sub level

    // Condition given path is equal to the URI's top level has been checked at the first line
    // Path and URI are at different level
    $index = -1;
    for($i = 0; $i < count($path) && $i < count($uri); $i++) {      // Remove common subfolders of the URI from the path
        if($uri[$i] === $path[$i]) {
            $index = $i;
        }
        else break;
    }
    if($index >= 0) {
        array_splice($path, 0, $i);
        array_splice($uri, 0, $i);
    }

    $out = '';
    for($i = 0; $i < count($uri); $i++) {
        $out .= ('..' . $separator);
    }
    $out .= implode($separator, $path);

    if(!str_ends_with($out, $separator) && !empty($out)) $out .= $separator;
    if(!empty($fileName)) $out .= $fileName;
    return $out;
}
