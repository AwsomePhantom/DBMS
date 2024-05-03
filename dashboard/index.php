<?php
session_start();
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_HEADERS'])) or die("Header related file not found");


global $user_obj;
$errorMsg = null;
$searchPage = relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR'] . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'search.php');

$cities = null;
// Get country code for searching nearby from user_obj
if(empty($user_obj->business)) {
    $cities = CONNECTION->getCities($user_obj->customer->address->country_code);
}
else {
    $cities = CONNECTION->getCities($user_obj->business->address->country_code);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<?php
    (include_once (relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR']) . 'pages' . DIRECTORY_SEPARATOR . 'menu.php')) or die("Failed to load component");
?>

<div class="container-fluid">
    <div class="row h-100">
        <div id="filterMenu" class="col-sm-12 col-md-4 col-lg-3 h-100">
            <div class="h-100 card">
                <div class="card-header">
                    <span class="card-title">Filter Menu</span>
                </div>
                <div class="card-body">

                    <form method="GET" id="searchForm">
                        <div class="form-group border border-light p-3">
                            <div class="row">
                                <div class="col">
                                    <label for="keyword">Search Post
                                        <input id="searchField" name="keyword" class="form-control" type="text" value="<?php echo !empty($_GET['keyword']) ? $_GET['keyword'] : null; ?>" placeholder="Keyword">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <button class="btn btn-primary" type="button" onclick="document.getElementById('searchField').value=''; document.getElementById('searchForm').submit()">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--
                    <form method="POST">
                        <div class="form-group border border-light p-3">
                            <div class="row">
                                <div class="col">
                                    <label for="searchGarageField">Search Garage By Work Nearby
                                        <input name="searchGarageField" class="form-control" type="text">
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <small class="text-muted">Enable device localisation or GPS</small><br>
                                    <button name="searchGarageButton" class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    -->
                    <form method="GET">
                        <div class="form-group border border-light p-3">
                            <div class="row">
                                <div class="col">
                                    <label for="city_id">City
                                        <select name="city_id" class="form-control form-select form-select-lg mb-3" aria-label="Cities">
                                            <option value="">All cities</option>
                                            <?php
                                            $temp = 'selected="selected"';
                                            $city_id = $user_obj->business ? $user_obj->business->address->city_id : $user_obj->customer->address->city_id;
                                                foreach($cities as $row) {
                                                    echo '<option value="' . $row['id'] . '"' . ($row['id'] === $city_id ? $temp : null) . '>' . $row['name'] . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="dateField">From date
                                        <input name="date" class="form-control" type="date">
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-primary" type="submit">Apply Filter</button>
                                    <button class="btn btn-secondary" type="submit">Cancel Filter</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8 col-lg-9">
            <?php
                if(!empty($_GET['keyword'])) {
                    (include_once ('pages/search.php')) or die("Failed to load component");
                }
                else if(!empty($_GET['city_id']) || !empty($_GET['date'])) {
                    (include_once ('pages/filter.php')) or die("Failed to load component");
                }
                else {
                    (include_once ('pages/posts.php')) or die("Failed to load component");
                }
            ?>
        </div>
    </div>
</div>

<?php if(isset($errorMsg)) {
    echo <<< ENDL_
    <div class="col-md-8 mx-auto my-2 fixed-bottom alert alert-info alert-dismissible fadein show" role="alert" style="z-index: 99999; position: fixed;">
        <strong>{$errorMsg}</strong> 
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
ENDL_;
}
?>

<script src="scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>