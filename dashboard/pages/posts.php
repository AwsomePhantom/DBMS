<?php
use classes\user;
$temp = $_SERVER['REQUEST_METHOD'];
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
///////////////////////////////////////////////////////////////////////////////////////

global $user_obj;
if(!($user_obj) instanceof user) {
    header("Location: " . relativePathSystem(ABSOLUTE_PATHS['LOGIN_PAGE']));
    //throw new Exception("Failed to decode user data object");
}

if(isset($_SERVER['REQUEST_METHOD']) == 'POST') {
    // Branch for new post submission
    if(isset($_POST['submitPostButton']) &&
    isset($_POST['titleField']) &&
    isset($_POST['cityField']) &&
    isset($_POST['addressField']) &&
    isset($_POST['contentField'])) {
        $res = CONNECTION->addNewPost($user_obj->id, $_POST['titleField'], $_POST['contentField'], $_POST['cityField'], $_POST['addressField']);
        if(!$res) {
            $errorMsg = 'Failed to upload the post!';
        }
        unset($_POST['submitPostButton']);
        unset($_POST['titleField']);
        unset($_POST['contentField']);
        unset($_POST['cityField']);
        unset($_POST['addressField']);
    }


    // Branch for delete post conditional
    if (isset($_POST['deletePostButton']) &&
        isset($_POST['post_id'])) {
        CONNECTION->deletePost($_POST['post_id'], $user_obj->id);
        unset($_POST['post_id']);
        unset($_POST['deletePostButton']);
    }
}

// Variables and array for social post display
$social_posts_array = CONNECTION->getAllPosts(null, null);
$cities = null;
// Get country code for posting from user_obj
if(empty($user_obj->business)) {
    $cities = CONNECTION->getCities($user_obj->customer->address->country_code);
}
else {
    $cities = CONNECTION->getCities($user_obj->business->address->country_code);
}

echo '<div id="posts">';

// Insert new Post Field
echo <<< ENDL_
<div class="card mb-3 mx-3">
                <div class="card-header">Write a post...</div>
                <div class="card-body p-3">
                    <form method="POST">
                    <input type="hidden" name="request_method" value="POST">
                        <div class="form-group">
                            <label for="titleField">Title</label>
                            <input name="titleField" class="form-control" type="text" placeholder="Need towing, help fixing, selling..." required>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <div class="row">
                                <div class="col-6">
                                    <label for="cityField">City</label>
                                    <select name="cityField" class="form-control form-select form-select-lg mb-3" aria-label="Cities" required>
ENDL_;
    foreach($cities as $row) {
        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
echo <<< ENDL_
                                    </select> 
                                </div>
                                <div class="col-6">
                                    <label for="addressField">Address</label>
                                    <input name="addressField" class="form-control" type="text" required>                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contentField">Content</label>
                            <textarea name="contentField" class="form-control" rows="4" cols="50" placeholder="Write something..." required></textarea>
                        </div>
                        <div class="form-group text-end">
                            <button name="submitPostButton" class="btn btn-lg btn-primary" type="submit">Post</button>
                            <button name="resetButton" class="btn btn-lg btn-secondary" type="reset">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
ENDL_;

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
        $offer_service = null;
        $date_time = new DateTime($row['date']);
        $replies_count = CONNECTION->getPostRepliesCount($row['post_id']);

        if ($row['user_id'] === $user_obj->id) {
            $deleteLink = '<button name="deletePostButton" class="btn btn-danger" type="submit"><i class="fa-solid fa-trash-can"></i> Delete Post</button>';
        }
        else if($user_obj->business) {
            $offer_service = '<button name="offerServiceButton" class="btn btn-warning" type="submit"><i class="bi bi-briefcase-fill"></i> Offer Service</button>';
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
                    {$deleteLink}{$offer_service}
                </div>
                <div class="col text-end">
                    <a class="link-primary" href="{$relative_to_dashboard}post_replies.php?post_id={$row['post_id']}"><i class="fa-regular fa-comment-dots"></i> Replay <span class="badge bg-secondary">{$replies_count}</span></a>
                    <span class="px-1 text-muted">|</span>
                    <a class="link-secondary" href="#">Secondary Link</a>
                </div>
            </div>
        </div>
    </div>
</form>
ENDL_;
    } // End of foreach loop
}   // End branch for many social posts found

echo '</div>';



