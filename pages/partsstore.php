<?php
session_start();
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
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'connection.php')) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parts Store</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
</head>
<body class="bg-body">

<?php
(include_once(relativePathSystem(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePathSystem(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");

?>

<div class="container bg-white m-5 mx-auto rounded" style="padding:70px;>
    <article class="mb-3">
        <h1>Genuine Spare Parts</h1>
        <hr>
        <p>Our partners offer genuine spare parts at very attractive prices. Your car gets the care it truly deserves. Equip your cars with only trusted spare parts</p>
        <ul style="list-style-type: none">
            <li><i class="bi bi-check2-square"></i> Improve your car performance</li>
            <li><i class="bi bi-check2-square"></i> Genuine & certified spares</li>
            <li><i class="bi bi-check2-square"></i> Serviced by qualified expert</li>
        </ul>
    </article>

    <article class="mb-3">
        <img class="img-fluid rounded border shadow-sm vw-100" alt="Car Wheel Servicing" src="<?php echo relativePath(ROOT_DIR) ?>/assets/picture03.jpg">
    </article>
</div>



<?php (include_once(relativePathSystem(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
</body>
</html>