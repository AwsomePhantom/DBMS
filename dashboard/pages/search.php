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
///////////////////////////////////////////////////////////////////////////////////////

global $user_obj;
$search_keyword = $_GET['search'];
if(!($user_obj instanceof user) || empty($search_keyword)) {
    header("Location: " . relativePath(ABSOLUTE_PATHS['DASHBOARD']));
    //throw new Exception("Failed to decode user data object");
}

if(isset($_SERVER['REQUEST_METHOD']) == 'POST') {
    // Branch for delete post conditional
    if (isset($_POST['deletePostButton']) &&
        isset($_POST['post_id'])) {
        CONNECTION->deletePost($_POST['post_id'], $user_obj->id);
        unset($_POST['post_id']);
        unset($_POST['deletePostButton']);
    }
}

// Variables and array for social post display
$social_posts_array = CONNECTION->searchPost($search_keyword);

echo '<div id="posts">';

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



