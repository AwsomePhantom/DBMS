<?php include_once($_SERVER['DOCUMENT_ROOT'].'/site_variables.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redirecting</title>
    <?php echo ABSOLUTE_PATHS['FULL_BOOTSTRAP']; ?>
</head>
<body class="rubik-regular bg-body-secondary">

<div class="vw-100 vh-100 bg-secondary" style="display: flex; align-items: center; justify-content: center;">
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
