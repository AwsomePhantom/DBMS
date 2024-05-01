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

<div id="posts">
    <div class="card shadow-sm m-3 mx-3">
        <div class="card-header lato-bold">
            Selling Toyota Allion G Package 2011
            <p class="card-subtitle text-secondary mt-0">Author: Bob Ross</p>
            <p class="card-subtitle text-secondary">Location: Jatrabari - Dhaka, Bangladesh</p>
            <p class="card-subtitle text-muted m-0">Date: 14/06/2015</p>
        </div>
        <div class="card-body">
            <p>
                Brand: Toyota   |
                Model: Allion   |
                Trim / Edition: G Package 2011  |
                Year of Manufacture: 2011  |
                Registration year: 2016   |
                Condition: Used   |
                Transmission: Automatic   |
                Body type: Saloon   |
                Fuel type: Octane, LPG   |
                Engine capacity: 1,500 cc   |
                Kilometers run: 56,000 km   |

                G-Package Push Start.<br>
                All Original & Smart Card.<br>
                Model:- 2011  |  Registration: 2016<br>
                Price negotiable
            </p>
        </div>
        <div class="card-footer text-end">
            <a class="link-primary" href="#"><i class="fa-regular fa-comment-dots"></i> Replay</a>
            <span class="px-1 text-muted">|</span>
            <a class="link-secondary" href="#">Secondary Link</a>
        </div>
    </div>

    <div class="card shadow-sm mb-3 mx-3">
        <div class="card-header lato-bold">
            Repair for BMW 7 Series 2016
            <p class="card-subtitle text-secondary mt-0">Author: Bob Spark</p>
            <p class="card-subtitle text-secondary">Location: </p>
            <p class="card-subtitle text-muted m-0">Date: 14/06/2022</p>
        </div>
        <div class="card-body">
            <p>
                Need emergency fix!<br>
                Car gear hardened, difficult to start from the 1st. It does a strange grinding sound!<br>
                Can bring the car personally, no towing needed
            </p>
        </div>
        <div class="card-footer text-end">
            <a class="link-primary" href="#">Send Message</a>
            <span class="px-1 text-muted">|</span>
            <a class="link-secondary" href="#">Secondary Link</a>
        </div>
    </div>
</div>