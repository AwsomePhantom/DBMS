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
<body class="bg-body-secondary">

<?php
(include_once(relativePathSystem(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePathSystem(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");

?>

<div class="container bg-white m-5 mx-auto rounded" style="padding:70px;>
<div class="row">
    <div class="col">
        <div class="card mb-3">
            <div class="card-img-top">
                <img class="rounded border shadow-sm" src="../assets/icon01.png" alt="Wheel" style="width: 100px">
            </div>
            <div class="card-body">
                <h5 class="card-title">Auto Maintenance Services</h5>
                <p class="card-text">Is your engine showing signs of wear and tear? Experiencing decreased performance, excessive oil consumption, or unusual noises? Our auto maintenance service is here to address these issues and bring your engine back to life.</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card mb-3">
            <div class="card-img-top">
                <img class="rounded border shadow-sm" src="../assets/icon02.jpg" alt="Wheel" style="width: 100px">
            </div>
            <div class="card-body">
                <h5 class="card-title">Brake Repair Pads & Rotors</h5>
                <p class="card-text">Regular brake service ensures that your vehicle can stop quickly and effectively when needed.</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card mb-3">
            <div class="card-img-top">
                <img class="rounded border shadow-sm" src="../assets/icon03.png" alt="Wheel" style="width: 100px">
            </div>
            <div class="card-body">
                <h5 class="card-title">Shocks, Struts Replacement</h5>
                <p class="card-text">We specialize in suspension diagnostics and repairs, carefully inspecting each component to identify any worn-out or damaged parts. By using high-quality replacement parts that meet or exceed manufacturer specifications, we ensure the longevity and reliability of your suspension system</p>
            </div>
        </div>
    </div>
</div>
</div>



<?php (include_once(relativePathSystem(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
</body>
</html>