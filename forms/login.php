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
//////////////////////////////////////////////////////////////////////////////////////////
    if(isset($_COOKIE['USER_TOKEN'])) {
        header("Location: " . relativePath(ABSOLUTE_PATHS['DASHBOARD']));
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['usernameField']) && isset($_POST['passwordField'])) {
            try {
                $user_obj = CONNECTION->login($_POST['usernameField'], $_POST['passwordField']);
                if($user_obj !== null) {
                    $_SESSION['USER_OBJ'] = serialize($user_obj);
                    setcookie('USER_TOKEN', (string)$user_obj->session_id, time() + (3600 * 24), '/');
                    header("Location: " . relativePath(ABSOLUTE_PATHS['DASHBOARD']));
                }
                else {
                    $errorMsg = "Invalid username or password";
                    unset($_POST['usernameField']);
                    unset($_POST['passwordField']);
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login page</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap')
        body {
            font-family: "Rubik", sans-serif;
            background-color: #f5f5f5;
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

    </style>
</head>
<!--
    List of variables:
        usernameField
        passwordField
        stayLoggedSwitch
        clearButton
        submitButton
        registerButton
-->
<body>
<div class="container-fluid vh-100">
    <div class="row" style="justify-content: center;">
        <div class="card shadow-lg m-5" style="border-radius: 25px; width: 400px">
            <form method="POST" class="form-signin">
                <input type="hidden" name="request_method" value="POST">
                <div class="card-header bg-dark text-light" style="border-top-left-radius: 25px; border-top-right-radius: 25px; background: linear-gradient(180deg, dimgray, black)">
                    <legend class="my-2 text-center"><strong>Login</strong></legend>
                </div>
                <div class="card-body p-5">
                    <div>
                        <label for="usernameField">Enter Credentials</label>
                        <input name="usernameField" tabindex="1" type="text" class="form-control" id="usernameField" placeholder="Username" autofocus>

                    </div>
                    <div class="mb-3">
                        <input name="passwordField" tabindex="2" type="password" class="form-control" id="passwordField" placeholder="Password">
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-check form-switch">
                                <input name="rememberUser" value="true" tabindex="3" class="form-check-input" type="checkbox" role="switch" id="stayLoggedSwitch">
                                <label class="form-check-label" for="stayLoggedSwitch">Stay logged in</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 px-2 justify-content-start">
                            <input type="submit" tabindex="4" id="loginButton" class="mb-1 btn btn-lg btn-primary" value="Login">
                    </div>
                    <hr style="margin-left: -20px; margin-right: -20px">
                    <div class="row justify-content-center">
                        <h6>Not a member yet? <a class="link-primary" href="<?php echo relativePath(ABSOLUTE_PATHS['CUSTOMER_REGISTRATION_FORM']); ?>">Sign up</a></h6>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if(isset($errorMsg)) {
        echo <<< ENDL_
    <div class="col-md-8 mx-auto my-2 fixed-top alert alert-warning alert-dismissible fadein show" role="alert" style="z-index: 99999; position: fixed;">
        <strong>Error:</strong> {$errorMsg}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
ENDL_;
        }
    ?>


</body>
</html>