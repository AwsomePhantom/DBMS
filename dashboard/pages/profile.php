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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Branch for delete post conditional
    if (isset($_POST['deletePostButton']) &&
        isset($_POST['post_id'])) {
        CONNECTION->deletePost($_POST['post_id'], $user_obj->id);
        unset($_POST['post_id']);
        unset($_POST['deletePostButton']);
    }
}

$social_posts_array = CONNECTION->getSingleUserPosts($user_obj->id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="../styles.css">

</head>
<body style="background-image: none">
<?php
(include_once (relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR']) . 'pages' . DIRECTORY_SEPARATOR . 'menu.php')) or die("Failed to load component");
?>

<div class="card m-3 p-3">
    <div class="row h-100">
        <div class="col-6">
            <h2>Personal information</h2>
            <table class="table table-striped">
                <?php
                /////////////////////////////// Profile information /////////////////////////////////////////////////
                $variable_array = array();
                $variable_array['mobile'] = count($user_obj->customer->contacts->phones) > 1 ? $user_obj->customer->contacts->phones : null;
                $variable_array['notes'] = empty($user_obj->customer->address->notes) ? null : $user_obj->customer->address->notes;
                $city_name = CONNECTION->getCityName($user_obj->customer->address->city_id);
echo <<< ENDL_
                <tr>
                    <td><strong>Username: </strong>{$user_obj->username}</td>
                    <td><strong>Email: </strong>{$user_obj->email}</td>
                    <td><strong>Registration Date: </strong>{$user_obj->registered->format('d M Y')}</td>
                </tr>
                <tr>
                    <td><strong>First Name: </strong>{$user_obj->customer->name}</td>
                    <td><strong>Last Name: </strong>{$user_obj->customer->lastname}</td>
                    <td><strong>Birthdate: </strong>{$user_obj->customer->birthdate->format('d M Y')}</td>
                </tr>
                <tr>
                    <td><strong>Gender: </strong>{$user_obj->customer->gender[0]}</td>
                </tr>
                <tr>
                    <td><strong>Phone: </strong>{$user_obj->customer->contacts->phones[0]}</td>
                    <td><strong>Mobile: </strong>{$variable_array['mobile']}</td>
                </tr>
                <tr>
                    <td><strong>Country: </strong>{$user_obj->customer->address->country_code}</td>
                    <td><strong>City: </strong>{$city_name}</td>
                    <td><strong>State / District: </strong>{$user_obj->customer->address->district}</td>
                </tr>
                <tr>
                    <td><strong>ZIP Code: </strong>{$user_obj->customer->address->zipCode}</td>
                    <td><strong>Address: </strong>{$user_obj->customer->address->street}</td>
                    <td><strong>Holding Number: </strong>{$user_obj->customer->address->holding}</td>
                </tr>
                <tr>
                    <td><strong>Additional Info: </strong>{$variable_array['notes']}</td>
                </tr>
ENDL_;
?>
            </table>
            <hr class="dropdown-divider my-5">
            <h2>Company information</h2>
            <table class="table table-striped">
                <?php
                    if($user_obj->business) {
                        $variable_array = array();
                        $variable_array['mobile'] = count($user_obj->business->contacts->phones) > 1 ? $user_obj->business->contacts->phones[1] : null;
                        $variable_array['notes'] = empty($user_obj->business->address->notes) ? null : $user_obj->business->address->notes;
                        $city_name = CONNECTION->getCityName($user_obj->business->address->city_id);
                        $office_weekdays = $user_obj->business->weekdays;
                        $office_weekdays = explode(',', $office_weekdays);
                        $office_weekdays = implode(', ', $office_weekdays);
echo <<< ENDL_
    <tr>
                    <td><strong>Company Name: </strong>{$user_obj->business->company_name}</td>
                    <td><strong>Company Type: {$user_obj->business->company_type}</strong></td>
                    <td><strong>Licence Number: </strong>{$user_obj->business->licence_number}</td>
                </tr>
                <tr>
                    <td><strong>Phone: </strong>{$user_obj->business->contacts->phones[0]}</td>
                    <td><strong>Mobile: </strong>{$variable_array['mobile']}</td>
                </tr>
                <tr>
                    <td><strong>Country: </strong>{$user_obj->business->address->country_code}</td>
                    <td><strong>City: </strong>{$city_name}</td>
                    <td><strong>State / District: </strong>{$user_obj->business->address->district}</td>
                </tr>
                <tr>
                    <td><strong>ZIP Code: </strong>{$user_obj->business->address->zipCode}</td>
                    <td><strong>Address: </strong>{$user_obj->business->address->street}</td>
                    <td><strong>Holding Number: </strong>{$user_obj->business->address->holding}</td>
                </tr>
                <tr>
                    <td><strong>Additional Info: </strong>{$variable_array['notes']}</td>
                </tr>
                <tr>
                    <td><strong>Office Weekdays: </strong>{$office_weekdays}</td>
                    <td><strong>Opening Hours: </strong>{$user_obj->business->start->format("H:i:s")}</td>
                    <td><strong>Closing Hours: </strong>{$user_obj->business->end->format("H:i:s")}</td>
                </tr>
                <tr>
                    <td><strong>Business open and running: </strong>{$user_obj->business->active}</td>
                </tr>
ENDL_;
                    }
                    else {
                        echo "<tr><td>No business records</td></tr>";
                    }
                ?>
            </table>
        </div>

        <div class="col-6">
<?php
////////////////////////////////////// Shared Posts Column //////////////////////////////////////////
echo '<div id="posts">';
echo "<h2 class='ps-3'>Published articles</h2>";

// Branch for no existing social posts
if(empty($social_posts_array)) {
    echo <<< ENDL_
    <div class="card shadow-sm bg-white p-3 text-center"><span class="lead">No social posts found!</span></div>
ENDL_;
}
// Branch for many social posts
else {
    $relative_to_dashboard = relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR'] . DIRECTORY_SEPARATOR . 'pages');
    foreach ($social_posts_array as $row) {
        $replies_count = null;
        $deleteLink = null;

        $date_time = new DateTime($row['date']);
        $replies_count = CONNECTION->getPostRepliesCount($row['post_id']);

        if ($row['user_id'] === $user_obj->id) {
            $deleteLink = '<button name="deletePostButton" class="btn btn-danger" type="submit"><i class="fa-solid fa-trash-can"></i> Delete Post</button>';
        }

        echo <<< ENDL_
<form method="POST"><input type="hidden" name="request_method" value="POST">
<input type="hidden" name="post_id" value="{$row['post_id']}">
    <div class="card shadow-sm m-3">
        <div class="card-header lato-bold">
            {$row['title']}
            <p class="card-subtitle text-secondary mt-0">Author: {$row['author']}</p>
            <p class="card-subtitle text-secondary">Location: {$row['address']} - {$row['city']}, {$row['country_code']}</p>
            <p class="card-subtitle text-muted m-0">Date: {$date_time->format("d M Y H:i")}</p>
        </div>
        <div class="card-body">
            <p>
                {$row['content']}
            </p>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {$deleteLink}
                </div>
                <div class="col text-end">
                    <a class="link-primary" href="{$relative_to_dashboard}post_replies.php?post_id={$row['post_id']}"><i class="fa-regular fa-comment-dots"></i> Replay <span class="badge bg-secondary">{$replies_count}</span></a>
                    <!--<span class="px-1 text-muted">|</span>
                    <a class="link-secondary" href="#">Secondary Link</a>-->
                </div>
            </div>
        </div>
    </div>
</form>
ENDL_;
    } // End of foreach loop
}   // End branch for many social posts found

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