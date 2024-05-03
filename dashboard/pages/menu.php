<?php
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_AUTH'])) or die("Connection related file not found");   // Check for user credentials

use classes\user;
global $user_obj;
?>
<style>
    #navbarSupportedContent .nav-link {
        color: white;
    }
    .navbar-collapse {
        text-align: left;
    }
    #mainMenu .btn {
        flex-shrink: 1;
        flex-grow: 1;
    }
</style>
<div class="container-fluid m-0">
    <div class="row vw-100">
        <div class="col">
            <div id="mainMenu" class="navbar navbar-expand-sm nav-pills nav-fill shadow-sm mb-3" style="background: linear-gradient(45deg, lightgrey, gray); border-radius: 5px; border: 1px black;">
                <div class="container-fluid">
                    <a class="navbar-brand">
                        <img src="../../assets/repair.png" class="img-simple-border rounded rounded-2 shadow-sm bg-white" alt="Garage Logo" style="width: 40px;">
                        Auto Car Repair Service
                        <span class="bi bi-grip-vertical h3"></span>
                        <span id="currentLocation" class="card-subtitle" style="font-size: 14px"></span>
                    </a>

                    <form method="POST">
                        <ul class="navbar-nav" style="font-weight: 600;">
                            <li class="nav-item pe-2"><a class="nav-link active" href="<?php echo relativePathSystem(ABSOLUTE_PATHS['DASHBOARD']); ?>"><i class="bi bi-house"></i> Home</a></li>
                            <li class="nav-item pe-2"><a class="btn btn-info" href="#"><i class="bi bi-person-circle"></i> Profile</a></li>
                            <li class="nav-item pe-2"><a class="btn btn-success" href="#"><i class="fa-solid fa-money-check-dollar"></i> Invoices <span class="badge bg-secondary">4</span></a></li>
                            <li class="nav-item pe-2"><a class="btn btn-danger" href="#"><i class="fa-solid fa-wrench"></i> Parts Store</a></li>
                            <li class="nav-item pe-2"><a class="btn btn-dark" href="#sidebar" data-bs-toggle="collapse"><i class="bi bi-sliders"></i> Settings</a></li>
                            <li class="nav-item"><span class="bi bi-grip-vertical h3"></span></li>
                            <li class="nav-item" style="padding-left: 10px"><button name="logoutButton" class="btn btn-block btn-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button></li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>

        <div id="sidebar" class="vh-100 mx-0 bg-dark collapse fade" style="z-index: 9999999; position: fixed; width:400px; box-shadow: 5px 0 20px gray">
            <div class="container-fluid">
                <div class="row text-end text-white">
                    <a class="my-3" data-bs-toggle="collapse" href="#sidebar"><i style="font-size:32px" class="fa-regular fa-circle-xmark text-warning"></i></a>
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
<a class="link-primary" href="#" onclick="getLocation()">
    <div class="bg-white p-3 shadow-lg" style="position: fixed; bottom: 50px; right: 50px; z-index: 99999; border-radius: 50px">
        <i style="font-size: 32px" class="fa-solid fa-location-crosshairs"></i>
    </div>
</a>

<script>
    window.onload = function () {
        document.addEventListener("click", function (event) {
            if (!event.target.closest("#sidebar") && document.getElementById("sidebar").classList.contains("show")) {
                document.getElementById('sidebar').classList.remove('show');
            }
        });
    }
</script>
