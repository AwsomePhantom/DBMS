<?php
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
?>
<nav class="container-fluid m-0 p-0">
    <ul class="nav flex-row justify-content-end" id="baseToolBar">
        <li><h5><a href="#" id="registerToolbarLink" class="nav-link" data-bs-toggle="modal" data-bs-target="#registerModal" onclick="toggleMenu();"><i class="fa-solid fa-user-plus"></i> Create an account</a></h5></li>
        <li><h5><a href="#" id="loginToolbarLink" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="toggleMenu();"><i class="fa-solid fa-right-to-bracket"></i> Login</a></h5></li>
    </ul>
</nav>

<div class="m-0 p-5 bg-light text-dark shadow-lg" id="jumbotron">
    <h1 class="pt-5">Auto Repair Services</h1>
    <span>Welcome to our site, your one-stop solution for all your automotive repair and maintenance needs</span>
    <br>
    <span>Our online mechanic workshop is designed to provide you with convenient and reliable services, right at your doorstep.</span>
</div>

<nav class="navbar navbar-expand-lg bg-light sticky-top shadow-sm">
    <div class="container-fluid">
        <i class="fa-solid fa-blog d-sm-block d-lg-none d-xl-block"></i>
        <a class="navbar-brand px-2 d-sm-block d-lg-none d-xl-block" href="#">Navigation Menu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto" style="min-width: 790px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo relativePath(ABSOLUTE_PATHS['HOME_PAGE']) ?>"><i class="fa-solid fa-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-circle-info"></i> About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-wrench"></i> Services
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Auto Repair Services</a></li>
                        <li><a class="dropdown-item" href="#">Brake Repair</a></li>
                        <li><a class="dropdown-item" href="#">Auto A/C Repair</a></li>
                        <li><a class="dropdown-item" href="#">Suspension Repair</a></li>
                        <li><a class="dropdown-item" href="#">Check Engine Light Diagnostic</a></li>
                        <li><a class="dropdown-item" href="#">Oil Change</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Estimation</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-star"></i> Special</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo relativePath(ABSOLUTE_PATHS['PARTS_STORE']); ?>"><i class="fa-solid fa-gears"></i> Parts Store</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-warehouse"></i> Find Nearby Garage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-phone"></i> Contacts</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-primary ml-2" type="submit">Search</button>
            </form>

            <!-- modal dialog for login -->
            <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <form method="POST" class="form-signin">
                                    <input type="hidden" name="request_method" value="POST">
                                    <div>
                                        <label for="loginUsernameField">Enter Username</label>
                                        <input name="loginUsernameField" tabindex="1" type="text" class="form-control" placeholder="Username" autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label for="loginPasswordField">Enter Password</label>
                                        <input name="loginPasswordField" tabindex="2" type="password" class="form-control" placeholder="Password">
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="form-check form-switch">
                                                <input tabindex="3" class="form-check-input" type="checkbox" role="switch" id="stayLoggedSwitch">
                                                <label class="form-check-label" for="stayLoggedSwitch">Stay logged in</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 m-1">
                                        <input tabindex="4" type="submit" id="loginButton" class="mb-1 btn btn-lg bg-primary text-light" value="Login">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <h5>Not a member yet? <a class="link-primary" href="#">Sign up</a></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal dialog for registering -->
            <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Registration</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Select which type of account do you want to create</p>
                            <ul>
                                <li><strong>Customer Account: </strong> for typical users.</li>
                                <li><strong>Business Account: </strong> specialized car mechanic, for garage owners, spare parts dealer.</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="location.href='<?php echo relativePath(ABSOLUTE_PATHS['CUSTOMER_REGISTRATION_FORM']); ?>';">Consumer Account</button>
                            <button type="button" class="btn btn-secondary" onclick="location.href='<?php echo relativePath(ABSOLUTE_PATHS['BUSINESS_REGISTRATION_FORM']); ?>';">Business Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

