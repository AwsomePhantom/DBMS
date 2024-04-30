<?php

if(!defined('ROOT_DIR')) {
    define("ROOT_DIR", __DIR__);
}
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
    "HOME_PAGE"                     => ROOT_DIR . DIRECTORY_SEPARATOR . 'index.php',
    "MENU_PAGE"                     => ROOT_DIR . DIRECTORY_SEPARATOR . 'home_components' . DIRECTORY_SEPARATOR . 'menu.php',
    "ARTICLES_PAGE"                 => ROOT_DIR . DIRECTORY_SEPARATOR . 'home_components' . DIRECTORY_SEPARATOR . 'articles.php',
    "FOOTER_PAGE"                   => ROOT_DIR . DIRECTORY_SEPARATOR . 'home_components' . DIRECTORY_SEPARATOR . 'footer.php',
    "CUSTOMER_REGISTRATION_FORM"    => ROOT_DIR . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'registration.php',
    "BUSINESS_REGISTRATION_FORM"    => ROOT_DIR . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'registrationBusiness.php',
    "LOGIN_PAGE"                    => ROOT_DIR . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'login.php',
    "LOADING_PAGE"                  => ROOT_DIR . DIRECTORY_SEPARATOR . 'home_components' . DIRECTORY_SEPARATOR . 'loading.php',
    "SUCCESSFUL_REGISTRATION"       => ROOT_DIR . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'successful_registration.php',
    "GLOBAL_STYLESHEET"             => ROOT_DIR . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . 'styles.css',
    "GLOBAL_SCRIPT"                 => ROOT_DIR . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'main.js',
    "CONNECTION"                    => ROOT_DIR . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'connection.php',
    "COUNTRIES"                     => ROOT_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'countries.php',
    "DASHBOARD"                     => ROOT_DIR . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'index.php'
);

if(!file_exists(ABSOLUTE_PATHS['HOME_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['MENU_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['ARTICLES_PAGE'])) die("Articles page file not found.");
if(!file_exists(ABSOLUTE_PATHS['FOOTER_PAGE'])) die("Footer file not found.");
if(!file_exists(ABSOLUTE_PATHS['GLOBAL_STYLESHEET'])) die("Stylesheet file not found.");
if(!file_exists(ABSOLUTE_PATHS['GLOBAL_SCRIPT'])) die("Stylesheet file not found.");

function b5_theme_link() : string {
    return  relativePath(ROOT_DIR . DIRECTORY_SEPARATOR . "precompiled" . DIRECTORY_SEPARATOR . $GLOBALS['USER_THEME'] . DIRECTORY_SEPARATOR . "bootstrap-color.min.css");
}

function relativePath($absolutePath, $separator = DIRECTORY_SEPARATOR) : string {
    if($absolutePath === ROOT_DIR) return '';

    $path = explode($separator, $absolutePath);
    $uri = explode('/', $_SERVER['REQUEST_URI']);
    $root = explode($separator, ROOT_DIR);
    $fileName = null;

    array_splice($path, 0, 1);      // remove first empty element
    array_splice($uri, 0, 1);       // remove first empty element
    array_splice($root, 0, 1);      // remove first empty element
	
	$temp = $uri[count($uri) - 1];
	$temp = explode('.', $temp);
	if(count($temp) > 0)  {
		array_pop($uri);                            // remove index.* file name from the URI, some browsers do not show index.* file
	}
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
    for($i = 0; $i < count($path) && $i < count($root); $i++) {
        if($root[$i] !== $path[$i]) {
            $index = $i;
            break;
        }
    }
    array_splice($path, 0, $i - 1);     // URI and Path at the same level
    if(implode($separator, $path) === implode($separator, $uri)) return $fileName;      // Same sub level

    // Equalise path and uri level and check if same page as level
    $index = -1;
    for($i = 0; $i < count($path); $i++) {	// case path = one/two/project/file.html and uri = one/two/prototype/file.html
        if($uri[0] === $path[$i]) {
            $index = $i;
            break;
        }
    }
    if($index > 0) {
        array_splice($path, 0, $i);
    }
	$index = -1;
    for($i = 0; $i < count($uri); $i++) {	// case path = /project/file.html and uri = [localhost]/one/two/project/file.html
        if($uri[$i] === $path[0]) {
            $index = $i;
            break;
        }
    }
    if($index > 0) {
        array_splice($uri, 0, $i);
    }
    if(implode($separator, $path) === implode($separator, $uri)) return '';


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
    return str_replace('\\', '/', $out);
}

/**
 * Replaces DIRECTORY SEPARATOR '/' with '\' for WINDOWS
 * @param string $path
 * @return string|null
 */
function relativePathSystem(string $path) : ?string {
    $out = relativePath($path);
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $out = str_replace('/', '\\', $out);
    }
    return $out;
}