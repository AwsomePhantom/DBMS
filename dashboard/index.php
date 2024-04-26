<?php
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once ($_SERVER['DOCUMENT_ROOT'] . '/site_variables.php')) or die("Variables file not found");
    $GLOBALS['WEBSITE_VARS'] = true;
}
if(!isset($GLOBALS['CONNECTION_VARS'])) {
    (require_once (relativePath(ABSOLUTE_PATHS['CONNECTION']))) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}

var_dump($_POST);
var_dump($_SERVER['REQUEST_METHOD']);
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logoutButton'])) {
        setcookie('USER_AUTH', '', time() - 3600);

        foreach ($_POST as $x) {
            unset($x);
        }
        header("Location: " . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
}

if(!isset($_COOKIE['USER_AUTH'])) {
    header("Location: " . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']));
    //echo "<script>window.location.href = '" . relativePath(ABSOLUTE_PATHS['LOGIN_PAGE']) . "';</script>";

}

$obj = unserialize($_COOKIE['USER_AUTH']);

if(!($obj instanceof \classes\user)) {
    echo "Error, user object is not set";
}
else {
    echo "<h4 class=\"border border-2\">";
    echo "Username: " . $obj->username;
    echo "<br>";
    echo "Name: " . $obj->customer->name . " " . $obj->customer->lastname;
    echo "<br>";
    //echo "Registered on: " . $obj->registered->format('Y-m-d H:i:s');
    echo "</h4>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo relativePath(ROOT_DIR . '/precompiled') . $GLOBALS['USER_THEME']; ?>/bootstrap-color.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['LOCAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['LOCAL_SCRIPTS']); ?>"></script>
    <title>Document</title>
</head>
<body>
<h1 class="col-6"><span class="bi-person"></span> User DashBoard</h1>
<form method="POST">
    <button name="logoutButton" class="btn btn-lg btn-primary" type="submit" value="exit">Logout</button>
</form>
</body>
</html>