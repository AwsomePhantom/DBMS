<?php
session_start();
const ROOT_DIR = __DIR__;
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . '/site_variables.php')) or die("Variables file not found");
    $GLOBALS['WEBSITE_VARS'] = true;
}
if(!isset($GLOBALS['CONNECTION_VARS'])) {
    (require_once (ROOT_DIR . '/database/connection.php')) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Document</title>
    <link rel="stylesheet" href="<?php echo relativePath(ROOT_DIR . '/precompiled') . $GLOBALS['USER_THEME']; ?>/bootstrap-color.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4!== $separatorLISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['LOCAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['LOCAL_SCRIPTS']); ?>"></script>
</head>
<body class="bg-body-secondary">

<?php
(include_once(relativePath(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePath(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");

?>

<div class="container bg-body my-5 mx-auto p-5 card" style="padding-top: 70px;">
    <?php (include_once(relativePath(ABSOLUTE_PATHS['ARTICLES_PAGE']))) or die("Failed to load component"); ?>
</div>



<?php (include_once(relativePath(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
</body>
</html>
