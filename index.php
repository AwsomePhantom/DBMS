<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index Document</title>
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.html'); ?>
    <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
</head>
<body class="bg-body-secondary">


<?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/home_components/menu.php'); ?>

<div class="container bg-body my-5 mx-auto p-5 card" style="padding-top: 70px">
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/home_components/articles.php'); ?>
</div>

<?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/home_components/footer.php'); ?>

</body>
</html>
