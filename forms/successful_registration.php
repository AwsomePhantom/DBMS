<?php
session_start();

if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redirecting</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
</head>
<body class="rubik-regular bg-body-secondary">

<div class="vw-100 vh-100 bg-white" style="display: flex; align-items: center; justify-content: center;">
    <div class="text-center">
        <div class="col">
            <div class="col mb-3">
                <span class="h3 lato-bold">Registration Successful</span>
            </div>
            <div class="col">
                <div class="spinner-border text-info" role="status" style="width: 100px; height: 100px;">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="col">
                <span class="text-muted">Redirecting...</span>
            </div>
        </div>
    </div>
</div>


<?php
    echo "<script>setTimeout(function() {window.location.href = '" . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']) . "';}, 5000);</script>";
?>

</body>
</html>
