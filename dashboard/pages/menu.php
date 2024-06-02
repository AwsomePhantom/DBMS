<?php
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
// form check in the dashboard_header in the index, profile and other pages...

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
                            <li class="nav-item pe-2"><a class="nav-link active" href="<?php echo relativePath(ABSOLUTE_PATHS['DASHBOARD']); ?>"><i class="bi bi-house"></i> Home</a></li>
                            <li class="nav-item pe-2"><a class="btn btn-info" href="<?php echo relativePath(ABSOLUTE_PATHS['DASHBOARD_DIR'] . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'profile.php'); ?>"><i class="bi bi-person-circle"></i> Profile</a></li>
                            <li class="nav-item pe-2"><a class="btn btn-warning" href="<?php echo relativePathSystem(($user_obj->business) ? ABSOLUTE_PATHS['DASHBOARD_RESCUE_BUSINESS'] : ABSOLUTE_PATHS['DASHBOARD_RESCUE']); ?>"><i class="fa-solid fa-helicopter"></i> Rescue <span class="badge bg-secondary"><?php echo ($user_obj->business ? CONNECTION->getAllIncidentsReportsCount() : CONNECTION->getUserIncidentsReportsCount($user_obj->id) ); ?></span></a></li>
                            <li class="nav-item pe-2"><a class="btn btn-success" href="<?php echo relativePathSystem(($user_obj->business) ? ABSOLUTE_PATHS['DASHBOARD_INVOICES_BUSINESS'] : ABSOLUTE_PATHS['DASHBOARD_INVOICES']); ?>"><i class="fa-solid fa-money-check-dollar"></i> Invoices <span class="badge bg-secondary"><?php echo ($user_obj->business ? CONNECTION->getTotalInvoicesCount() : CONNECTION->getUserInvoicesCount($user_obj->customer->id) ); ?></span></a></li>
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
                        <form method="POST">
                            <label class="bg-secondary border rounded rounded-2 p-2" for="theme">Theme
                                <select name="theme" class="form-control form-select form-select-lg mb-3" aria-label="Themes">
                                <?php
                                    $temp = ' selected="selected"';
                                    $i = 0;
                                    foreach (USER_THEMES as $theme) {
                                        echo "<option value='" . $i++ . "'" . ($GLOBALS['USER_THEME'] === $theme ? $temp : null) . ">" . $theme . "</option>";
                                    }
                                ?>
                                </select>
                                <button class="btn btn-primary" type="submit">Change</button>
                            </label>
                        </form>
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
