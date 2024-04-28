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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap')
        body {
            font-family: "Rubik", sans-serif;
            background-color: #f5f5f5;
            height: 100%;
        }
        legend {
            font-weight: lighter;
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
    <form class="form-signin">
        <legend class="mb-3 text-center"><strong>Login</strong></legend>
            <div class="form-floating">
                <input tabindex="1" type="text" class="form-control" id="usernameField" placeholder="Username" autofocus>
                <label for="usernameField">Username</label>
            </div>
        <div class="form-floating mb-3">
            <input tabindex="2" type="password" class="form-control" id="passwordField" placeholder="Password">
            <label for="passwordField">Password</label>
        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="form-check form-switch">
                    <input tabindex="3" class="form-check-input" type="checkbox" role="switch" id="stayLoggedSwitch">
                    <label class="form-check-label" for="stayLoggedSwitch">Stay logged in</label>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="row mb-3">
                <input tabindex="4" id="loginButton" class="mb-1 btn btn-lg btn-primary" value="Login">
                <input tabindex="5" id="registerButton" class="btn btn-lg btn-secondary" value="Register">
        </div>
    </form>
</div>


</body>
</html>