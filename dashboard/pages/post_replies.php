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

use classes\user;
global $user_obj;
$errorMsg = null;
$page_uri = getURI();
$post_id = $_GET['post_id'];


if(empty($post_id)) {
    $errorMsg = "Content not found!";
    exit("Page not found!");
}
else {
    $social_posts_array = CONNECTION->getSinglePost($post_id);
    if (empty($social_posts_array)) {
        $errorMsg = "Post not found";
        exit("Page not found!");
    }
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['submitMessageButton']) && !empty($_POST['messageField'])) {
        $res = CONNECTION->addPostReply($post_id, $user_obj->id, $_POST['messageField']);
        unset($_POST['submitMessageButton']);
        unset($_POST['messageField']);
        unset($_SERVER['REQUEST_METHOD']);
        if(!$res) {
            $errorMsg = "Error while adding reply";
        }
        //header("Location: " . $page_uri);
    }

    if(isset($_POST['deleteReplyButton']) && isset($_POST['reply_id'])) {
        $res = CONNECTION->deletePostReply($_POST['reply_id'], $post_id, $user_obj->id);
        unset($_POST['reply_id']);
        unset($_POST['deleteReplyButton']);
        unset($_SERVER['REQUEST_METHOD']);
        if(!$res) {
            $errorMsg = "Error while deleting reply";
        }
        //header("Location: " . $page_uri);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Replay - <?php if(!empty($social_posts_array)) echo $social_posts_array['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<?php
(include_once ('menu.php')) or die("Failed to load component");
echo '<div class="container">';

$date_time = new DateTime($social_posts_array['date']);
$home_url = relativePathSystem(ABSOLUTE_PATHS['DASHBOARD']);
echo <<< ENDL_
<div class="card shadow-sm m-3">
        <div class="card-header lato-bold">
            <div style="position: absolute; top: 5px; right: 5px">
                <a class="btn btn-danger" href="$home_url"><i class="fa-solid fa-circle-arrow-left"> Back</i></a>
            </div>
            <p class="card-title mt-0">Title: {$social_posts_array['title']}</p>
            <p class="card-subtitle mt-0">Author: {$social_posts_array['author']}</p>
            <p class="card-subtitle">Location: {$social_posts_array['address']} - {$social_posts_array['city']}, {$social_posts_array['country_code']}</p>
            <p class="card-subtitle text-muted m-0">Date: {$date_time->format("d M Y H:i")}</p>
        </div>
        <div class="card-body px-3">
            <p>
                {$social_posts_array['content']}
            </p>
        </div>
        <div class="card-footer">
ENDL_;
        $replies_array = CONNECTION->getPostReplies($post_id);
        foreach($replies_array as $reply) {
            $date_time = new DateTime($reply['date']);
            $delete_reply_link = null;
            if($reply['user_id'] == $user_obj->id) {    // for each reply if the user is the author
                $delete_reply_link = "<div class='text-end'>" .
                "<form method='POST'>" .
                "<input type='hidden' name='REQUEST_METHOD' value='POST'>" .
                "<input type='hidden' name='reply_id' value='{$reply['id']}'>" .
                "<button name='deleteReplyButton' class='link-danger border-0 bg-white' type='submit'>Delete Message</button></form></div>";
            }
echo <<< ENDL_
            <div class="my-3 p-2 rounded-3 border border-primary bg-white">
                <div><strong>Author:</strong> {$reply['author']}</div>
                <div><strong>Date:</strong> {$date_time->format('d M Y H:i')}</div>
                <div><strong>Message:</strong><br>{$reply['content']}</div>
ENDL_;

            if($reply['user_id'] === $user_obj->id) {    // for each reply if the user is the author
echo <<< ENDL_
            <div class='text-end'>
                <form method='POST'>
                    <input type="hidden" name="request_method" value="POST">
                    <input type='hidden' name='reply_id' value='{$reply['id']}'>
                    <button name='deleteReplyButton' class='link-danger border-0 bg-white' type='submit'>Delete Message</button>
                </form>
            </div>
ENDL_;
            }   // end of if conditional
            echo "</div> <!-- end of reply message body -->";
        }   // end of foreach loop for each post reply

echo <<< ENDL_
            <div class="form-group">
                <form method="POST" name="messageForm">
                    <label for="messageField">Write a message</label>
                    <textarea name="messageField" class="form-control" rows="3" cols="50" required></textarea>
                    <div class="text-end">
                        <button name="submitMessageButton" class="btn btn-primary mt-2" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div> <!-- end div .card-footer -->
    </div>  <!-- end div .card -->
ENDL_;
    if(isset($errorMsg)) {
echo <<< ENDL_
    <div class="col-md-8 mx-auto my-2 fixed-bottom alert alert-info alert-dismissible fadein show" role="alert" style="z-index: 99999; position: fixed;">
        <strong>Error:</strong> {$errorMsg}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
ENDL_;
}
?>
</div> <!-- end of .container -->
<script src="../scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
