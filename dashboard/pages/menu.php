<?php
session_start();
use classes\user;
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
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
<style>
    #navbarSupportedContent .nav-link {
        color: white;
    }
    .navbar-collapse {
        text-align: left;
    }
</style>
<div class="container-fluid m-0">
    <div class="row vw-100">
        <div class="col">
            <div id="mainMenu" class="navbar navbar-expand-sm nav-pills nav-fill shadow-sm mb-3" style="background: linear-gradient(45deg, lightgrey, gray); border-radius: 5px; border: 1px black;">
                <div class="container-fluid">
                    <a class="navbar-brand">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-android" viewBox="0 0 16 16">
                            <path d="M2.76 3.061a.5.5 0 0 1 .679.2l1.283 2.352A8.9 8.9 0 0 1 8 5a8.9 8.9 0 0 1 3.278.613l1.283-2.352a.5.5 0 1 1 .878.478l-1.252 2.295C14.475 7.266 16 9.477 16 12H0c0-2.523 1.525-4.734 3.813-5.966L2.56 3.74a.5.5 0 0 1 .2-.678ZM5 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m6 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                        </svg>
                        Dashboard
                        <span class="bi bi-grip-vertical h3"></span>
                    </a>

                    <form method="POST">
                        <ul class="navbar-nav" style="font-weight: 600;">
                            <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-house"></i> Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-person-circle"></i> Profile</a></li>
                            <li class="nav-item"><a class="nav-link" ><i class="bi bi-sliders"></i> Settings</a></li>
                            <li class="nav-item"><a class="btn btn-dark" href="#sidebar" data-bs-toggle="collapse"><i class="bi bi-layout-sidebar-inset h5 text-white"></i> Sidebar</a></li>
                            <li class="nav-item"><span class="bi bi-grip-vertical h3"></span></li>
                            <li class="nav-item" style="padding-left: 10px"><button name="logoutButton" class="btn btn-block btn-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button></li>
                        </ul>
                    </form>
                </div>
            </div>

            <span id="test"></span>
        </div>
        <div id="sidebar" class="vh-100 mx-0 bg-dark collapse fade" style="z-index: 9999999; position: fixed; width:400px; box-shadow: 5px 0 20px gray">
            <div class="container-fluid">
                <div class="row text-end text-white">
                    <a class="my-3" data-bs-toggle="collapse" href="#sidebar"><i style="font-size:32px" class="fa-regular fa-circle-xmark"></i></a>
                </div>
                <div class="row">
                    <div class="navbar navbar-dark bg-dark flex-column">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="#">Item 1</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Item 2</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Item 3</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Item 4</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Item 5</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        document.addEventListener("click", function (event) {
            if (!event.target.closest("#sidebar") && document.getElementById("sidebar").classList.contains("show")) {
                document.getElementById('sidebar').classList.remove('show');
            }
        });
    }
</script>
