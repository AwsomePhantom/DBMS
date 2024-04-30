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
    (require_once (relativePath(ABSOLUTE_PATHS['CONNECTION']))) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4!== $separatorLISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/styles/styles.css">
    <script src="/scripts/main.js"></script>
    <style>
        .card {
            margin: 15px;
            float: left;
            box-shadow: 2px 2px 10px gray;
        }
    </style>
</head>
<body>
<div class="container-lg p-2 float-sm-start">
    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-dark text-white" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-danger" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-secondary" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>

    <div class="card" style="width: 30ch">
        <div class="card-img-top bg-info" style="height: 6vw">
            <div class="card-title p-1 align-middle">Work type</div>
        </div>
        <div class="card-body">
            <div class="card-title">Post Title</div>
            <div class="card-subtitle mb-2 text-muted">Location</div>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>
        </div>
    </div>
    <div id="sample"></div>
</div>

<script>
    let x = document.getElementById('sample');

    function test() {
        x.innerHTML += '<div class="card" style="width: 30ch">' +
            '<div class="card-img-top bg-light" style="height: 6vw">' +
            '<div class="card-title p-1 align-middle">Work type</div>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="card-title">Post Title</div>' +
            '<div class="card-subtitle mb-2 text-muted">Location</div>' +
            '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>' +
            '<a href="#" onclick="test()" class="btn btn-primary float-end">See post</a>' +
            '</div>' +
            '</div>';
    }
</script>

</body>
</html>
