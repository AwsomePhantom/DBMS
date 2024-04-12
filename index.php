<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/site_variables.php'); ?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Document</title>
    <?php echo ABSOLUTE_PATHS['FULL_BOOTSTRAP']; ?>
    <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
</head>
<body class="bg-body-secondary">

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/home_components/loading.php');
include(ABSOLUTE_PATHS['MENU_PAGE']);

?>

<div class="container bg-body my-5 mx-auto p-5 card" style="padding-top: 70px;">
    <?php include_once(ABSOLUTE_PATHS['ARTICLES_PAGE']); ?>
</div>

<?php echo include_once(ABSOLUTE_PATHS['FOOTER_PAGE']); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
</script>
</body>
</html>
