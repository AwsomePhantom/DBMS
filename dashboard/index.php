<?php
session_start();
use classes\user;
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
    $GLOBALS['WEBSITE_VARS'] = true;
}
if(!isset($GLOBALS['CONNECTION_VARS'])) {
    (require_once (relativePath(ABSOLUTE_PATHS['CONNECTION']))) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['logoutButton'])) {
        unset($_POST['logoutButton']);
        $user_obj = unserialize($_SESSION['USER_OBJ']);
        if($user_obj instanceof user) {
            try {
                CONNECTION->logout($user_obj);
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
        setcookie('USER_TOKEN', '', time() - (3600 * 24 * 30), '/');
        session_unset();
        session_destroy();
        header('Location: ' . relativePath(ABSOLUTE_PATHS['HOME_PAGE']));
    }
}

if(isset($_COOKIE['USER_TOKEN'])) {
    $user_obj = unserialize($_SESSION['USER_OBJ']);
    if(!($user_obj instanceof user) || $user_obj->session_id !== $_COOKIE['USER_TOKEN']) {
        setcookie('USER_TOKEN', '', time() - (3600 * 24 * 30), '/');
        session_unset();
        session_destroy();
        header('Location: ' . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
    }
    else {
        $output = (string)$user_obj;
    }
}
else {
    session_unset();
    session_destroy();
    header("Location: " . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
}
$errorMsg = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<?php
    (include_once ('pages/menu.php')) or die("Failed to load component");
?>

<div class="container-fluid">
    <div class="row h-100">
        <div id="filterMenu" class="col-sm-12 col-md-4 col-lg-3 h-100">
            <div class="h-100 card">
                <div class="card-header">
                    <span class="card-title">Filter Menu</span>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8 col-lg-9">
            <?php (include_once ('pages/posts.php')) or die("Failed to load component"); ?>
        </div>
    </div>
</div>

<?php if(isset($errorMsg)) {
    echo <<< ENDL_
    <div class="col-md-8 mx-auto my-2 fixed-bottom alert alert-info alert-dismissible fadein show" role="alert" style="z-index: 99999; position: fixed;">
        <strong>Error:</strong> {$errorMsg}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
ENDL_;
}
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>