<?php
session_start();
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_HEADERS'])) or die("Header related file not found");
global $user_obj;
$errorMsg = null;
$form_url = getURI();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $errorMsg;
    // open account // double check later
    if(isset($_POST['report_id']) && isset($_POST['company_name'])) {

    }

    if(isset($_POST['offerServiceButton']) && isset($_POST['report_id'])) {
        $res = CONNECTION->addRepairRequest($_POST['report_id'], $user_obj->id);
        if(!$res) {
            $errorMsg = "Failed to add repair request";
        }
        header("Location: " . $form_url);;
    }

    if(isset($_POST['cancelServiceOffer']) && isset($_POST['report_id'])) {
        $res = CONNECTION->cancelRepairRequest($_POST['report_id'], $user_obj->id);
        if(!$res) {
            $errorMsg = "Failed to perform the request, please try again";
        }
        header("Location: " . $form_url);;
    }

    if(isset($_POST['deleteIncidentButton']) && isset($_POST['report_id'])) {
        CONNECTION->deleteIncidentReport($user_obj->id, $_POST['report_id']);
        header("Location: " . $form_url);;
    }
}

$social_posts_array = CONNECTION->getAllIncidentsReports();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rescue Page - List of emergency posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="../styles.css">

</head>
<body style="background-image: none">
<?php
(include_once (relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR']) . 'pages' . DIRECTORY_SEPARATOR . 'menu.php')) or die("Failed to load component");
$incidents_list = CONNECTION->getAllIncidentsReports();
?>

<div class="card m-3 p-3">
    <div class="row h-100">
        <div class="col">
            <?php
            if(empty($incidents_list)) {
echo <<< ENDL_
            <h3>No incidents reported</h3>
ENDL_;
            }
            else {
                $i = 0;
                foreach($incidents_list as $incident) {
                    $i++;
                    $repair_requests = array();
                    $repair_requests = CONNECTION->listRepairRequests($incident['user_id']);
                    echo "<form method='POST' class='mb-5'>";
                    echo "<input type='hidden' name='report_id' value='{$incident['id']}'>";
                    echo "<h3>{$i}# Rescue</h3>";
                    echo "<table class='table table-striped table-bordered'>" .
                        "<tr><td><strong>Author:</strong> {$incident['author']}</td><td><strong>Date:</strong> {$incident['date']}</td><td colspan='2'><strong>GPS:</strong> [{$incident['gpsx']}, {$incident['gpsy']}]</td></tr>" .
                        "<tr><td><strong>Address:</strong> {$incident['address']}</td><td><strong>District/City:</strong> {$incident['district']}</td><td><strong>Country:</strong> {$incident['country_code']}</td></tr>" .
                        "<tr><td colspan='3'><strong>Message Title:</strong> {$incident['title']}</td><tr>" .
                        "<tr><td colspan='3'><strong>Message:</strong> {$incident['message']}</td></tr>";
                    echo "<tr><td colspan='3'><strong>Options</strong></td></tr>";
                    if($user_obj->id === $incident['user_id'])  {
                        echo "<tr><td colspan='3'><button name='deleteIncidentButton' class='btn btn-danger' type='submit' value=''>Delete request</button></td></tr>";
                    }
                    $res = CONNECTION->isRepairRequested($incident['id'], $user_obj->id);
                    if(!$res && $user_obj->id !== $incident['user_id']) {
                        echo "<tr><td colspan='3'><button name='offerServiceButton' class='btn btn-warning' type='submit' value=''><i class='bi bi-briefcase-fill'></i> Offer Service</button></td></tr>";
                    }
                    if($res) {
                        echo "<tr><td colspan='3'><button name='cancelServiceOffer' class='btn btn-danger' type='submit' value=''><i class='bi bi-x-square-fill'> Cancel Offer</button></td></tr>";
                    }
                    echo "<tr><td colspan='3'><strong>Repair offers</strong></td></tr>";
                    if(!empty($repair_requests)) {
                        foreach ($repair_requests as $request) {
                            echo "<tr><td>Company: {$request['company_name']}</td><td>Company Type: {$request['company_type']}</td><td>{$request['email']}</td></tr>";
                            echo "<tr><td><strong>Options</strong></td></tr>";
                            echo "<button name='company_name' class='btn btn-primary' type='submit' value='{$request['company_name']}'><i class='fa-solid fa-thumbs-up'></i> Accept</button>";
                            echo "<tr><td></td></tr>";
                        }

                    }
                    else {
                        echo '<tr><td colspan="3">No servicing offer</td></tr>';
                    }
                    echo '</table>';
                    echo '</form>';
                }
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
<script href="<?php echo relativePath(ROOT_DIR . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'scripts.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>