<?php
require_once ('site_variables.php'); ?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Document</title>
    <link rel="stylesheet" href="precompiled/<?php echo $GLOBALS['USER_THEME']; ?>/bootstrap-color.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="scripts/main.js"></script>
</head>
<body class="bg-body-secondary">

<?php
include(ABSOLUTE_PATHS['LOADING_PAGE']);
include(ABSOLUTE_PATHS['MENU_PAGE']);

?>

<div class="container bg-body my-5 mx-auto p-5 card" style="padding-top: 70px;">
    <?php include_once(ABSOLUTE_PATHS['ARTICLES_PAGE']); ?>
</div>

<?php include_once(ABSOLUTE_PATHS['FOOTER_PAGE']); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
</body>
</html>
