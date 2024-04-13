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
    "LOCAL_STYLESHEET"              => ROOT_DIR . '/styles/styles.css'
);

if(!file_exists(ABSOLUTE_PATHS['HOME_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['MENU_PAGE'])) die("Menu file not found.");
if(!file_exists(ABSOLUTE_PATHS['ARTICLES_PAGE'])) die("Articles page file not found.");
if(!file_exists(ABSOLUTE_PATHS['FOOTER_PAGE'])) die("Footer file not found.");
if(!file_exists(ABSOLUTE_PATHS['LOCAL_STYLESHEET'])) die("Stylesheet file not found.");

function relativePath($absolutePath, $separator = DIRECTORY_SEPARATOR) : string {
    if($absolutePath === ROOT_DIR) return '';                  // The path given and the server root are identical

    $a = explode($separator, $absolutePath);
    $b = explode($separator, $_SERVER['REQUEST_URI']);
    $fileName = '';

    array_splice($a, 0, 1);                                  // Remove first empty element
    array_splice($b, 0, 1);                                  // Remove first empty element

    $b = array_slice($b, 0, count($b) - 1);                         // Remove last element from URI representing the webpage file

    if(is_file($absolutePath)) {                                                // If absolutePath is a file, remove filename from the path to compare directories
        $fileName = end($a);                                              // Store the filename
        $a = array_slice($a, 0, count($a) - 1);
    }
    else if(!is_dir($absolutePath)) {                                           // The absolute path given is not a file's address, nor a correct directory path
        return '';
    }

    if(implode($separator, $a) === ROOT_DIR) {                                  // The given path and the document root are the same, the relative path is the filename
        return $fileName;
    }

    /* URI is top level domain, compare with ROOT_DIR */
    if(count($b) == 0) {                                                        // The request URI is from the top level domain count($b) == 0, so there are no subfolders
        $c = explode($separator, ROOT_DIR);                               // Compare from the ROOT DIRECTORY of the website, no need to go backward as the URI is from the top level
        array_splice($c, 0, 1);                              // Remove first empty element
        $index = 0;

        for($i = 0; $i < count($a) && $i < count($c); $i++) {
            if($a[$i] === $c[$i]) {                                              // Compare the given path with the ROOT DIRECTORY to remove common folders from the relative path
                $index = $i;
            }
            else break;
        }

        $a = array_slice($a, $index);                                           // Remove the common directories from the absolute path
        if(empty($fileName)) return implode($separator, $a);
        return implode($separator, $a) . '/' . $fileName;                       // Return the relative path, not the ROOT DIRECTORY that is already excluded, so add a slash
    }

    /* URI is not top level, compare with the absolute path */
    // URI: [localhost]/one/two/three
    // Path: /var/www/htdocs/one/two/orange/one
    // Trim up to /two/orange/one   -> remove also one
    for($i = 0; $i < count($a) && count($b) > 0; $i++) {                        // The request URI has subfolders
        if($b[0] === $a[$i]) {                                                  // Compare the top level of the URI with given path until similar level
            array_splice($a, 0, $i + 1);                      // Exclude common directories path found up to the URI's top level
            break;
        }
    }
    array_splice($b, 0, 1);

    // URI: [localhost]/one/two
    // Path: /one/two
    if($a === $b) return $fileName;                                             // URI and absolute path are in the same level directory

    // Calculate super or sub folder relative path from a same common level directory
    $index = -1;
    $out = '';

    // Remove common prefix from URI and the Absolute Path
    // URI: [localhost]/tree
    // Path: /orange/one
    for($i = 0; $i < count($a) && $i < count($b); $i++) {
        if($a[$i] === $b[$i]) {
            $index = $i;
        }
        else break;
    }
    if($index >= 0) {
        $a = array_slice($a, $index + 1);
        $b = array_slice($b, $index + 1);
    }

    // Add super path
    for($i = 0; $i < count($b); $i++) {
           $out .= '../';
    }

    $out .= implode($separator, $a);
    if(!str_ends_with($out, '/') && empty($fileName)) return $out .= '/';
    else return $out .= '/' . $fileName;
}
