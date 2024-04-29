<?php
$arr = explode(DIRECTORY_SEPARATOR, __DIR__);
$arr = array_slice($arr, 0, count($arr) - 1);
define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
    if(!isset($GLOBALS['WEBSITE_VARS'])) {
        (require_once (ROOT_DIR . '/site_variables.php')) or die("Variables file not found");
        $GLOBALS['WEBSITE_VARS'] = true;
    }
    if(!isset($GLOBALS['CONNECTION_VARS'])) {
        (require_once (ROOT_DIR . '/database/connection.php')) or die("Connection related file not found");
        $GLOBALS['CONNECTION_VARS'] = true;
    }

    if(isset($_COOKIE['USER_AUTH'])) {
        header("Location: " . relativePath(ABSOLUTE_PATHS['DASHBOARD']));
        //echo "<script>window.location.href = '" . relativePath(ABSOLUTE_PATHS['DASHBOARD']) . "';</script>";

    }

    $uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['usernameField']) && isset($_POST['passwordField'])) {
            try {
                //$userObj = CONNECTION->login($_POST['usernameField'], $_POST['passwordField']);
                $str = 'mysql:host='.CONN_INFO['HOST'].';dbname='.CONN_INFO['DBNAME'].';charset=utf8mb4;';
                $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH];
                $pdo = new PDO($str, CONN_INFO['USERNAME'], CONN_INFO['PASSWORD']);
                $sql = "SELECT * FROM user_accounts WHERE username = ? AND password = ? LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $res = $stmt->execute([
                    $_POST['usernameField'],
                    $_POST['passwordField']
                ]);
                if($stmt->rowCount() <= 0) {
                    echo "No user found!";
                    echo "<h1>Login failed</h1>";
                    foreach($_POST as $X) {
                        unset($x);
                    }
                    return;
                }

                $row = $stmt->fetch();
                $user_id = $row['id'];
                $username = $row['username'];
                $customer_id = $row['customer_id'];

                $sql = 'SELECT * FROM customers_info WHERE id = ?';
                $stmt = $pdo->prepare($sql);
                $res = $stmt->execute([$customer_id]);;
                $row = $stmt->fetch();
                $customer = new \classes\customer($customer_id, $row['name'], $row['lastname'], new DateTime($row['birthdate']), $row['gender'], CONNECTION->get_customer_contacts($customer_id), CONNECTION->get_customer_address($customer_id));
                $userObj = new \classes\user($user_id, $username, $customer, null, null, null, null);
                echo "<script>console.log(\"Login successful\")</script>";

                if(isset($_POST['rememberUser'])) {
                    $expiry = time() + (3600 * 24 * 30); // 30 days
                    setcookie('USER_AUTH', serialize($userObj), $expiry, '/');
                }
                else {
                    setcookie('USER_AUTH', serialize($userObj), time() + 3600, '/');
                }

                //header("Location: " . relativePath(ABSOLUTE_PATHS['DASHBOARD']));
                echo "<script>window.location.href = '" . relativePath(ABSOLUTE_PATHS['DASHBOARD']) . "';</script>";



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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap 5 Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
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
<div class="container-fluid">
    <form method="POST" class="form-signin">
        <legend class="mb-3 text-center"><strong>Login</strong></legend>
            <div class="form-floating">
                <input name="usernameField" tabindex="1" type="text" class="form-control" id="usernameField" placeholder="Username" autofocus>
                <label for="usernameField">Username</label>
            </div>
        <div class="form-floating mb-3">
            <input name="passwordField" tabindex="2" type="password" class="form-control" id="passwordField" placeholder="Password">
            <label for="passwordField">Password</label>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-check form-switch">
                    <input name="rememberUser" value="true" tabindex="3" class="form-check-input" type="checkbox" role="switch" id="stayLoggedSwitch">
                    <label class="form-check-label" for="stayLoggedSwitch">Stay logged in</label>
                </div>
            </div>
        </div>
        <div class="row mb-3 m-1">
                <input type="submit" tabindex="4" id="loginButton" class="mb-1 btn btn-lg btn-primary" value="Login">
        </div>
        <hr>
        <div class="row text-center">
            <h6>Not a member yet? <a class="link-primary" href="#">Sign up</a></h6>
        </div>
    </form>
</div>


</body>
</html>