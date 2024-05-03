<?php
session_start();
if(!defined('ROOT_DIR')) {
    define("ROOT_DIR", __DIR__);
}
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
(include (relativePathSystem(ABSOLUTE_PATHS['SITE_HEADERS']))) or die("Headers file not found");    // Needed to update COOKIES for the menu bar
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Document</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
    <link rel="stylesheet" href="precompiled/simplex/bootstrap-color.min.css">
</head>
<body class="bg-body merriweather-regular">

<?php
(include_once(relativePathSystem(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePathSystem(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");

?>

<div class="container bg-white m-5 mx-auto rounded" style="padding:70px;">
    <?php (include_once(relativePathSystem(ABSOLUTE_PATHS['ARTICLES_PAGE']))) or die("Failed to load component"); ?>
</div>



<?php (include_once(relativePathSystem(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
<script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>