<?php
session_start();

if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 1);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}

if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
    $GLOBALS['WEBSITE_VARS'] = true;
}
if(!isset($GLOBALS['CONNECTION_VARS'])) {
    (require_once (ROOT_DIR . '/database/connection.php')) or die("Connection related file not found");
    $GLOBALS['CONNECTION_VARS'] = true;
}

use classes\business;
use classes\contacts;
use classes\address;
use classes\customer;
use classes\user;

define("REGISTRATION_POST_URI", getURI());

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if((!isset($_SESSION['REGISTERING_USER'])) &&
        isset($_POST['usernameField']) &&
        isset($_POST['passwordField']) &&
        isset($_POST['repeatPasswordField']) &&
        isset($_POST['emailField']) &&
        isset($_POST['firstNameField']) &&
        isset($_POST['lastNameField']) &&
        isset($_POST['birthDateField']) &&
        isset($_POST['genderRadio']) &&
        isset($_POST['customerNumberField1']) &&
        isset($_POST['customerNumberField2']) &&
        isset($_POST['customerCountryField']) &&
        isset($_POST['customerCityField']) &&
        isset($_POST['customerStateField']) &&  // district
        isset($_POST['customerZipCodeField']) &&
        isset($_POST['customerAddressField']) &&    // street
        isset($_POST['customerHoldingNumberField']) &&
        isset($_POST['customerNotesField'])) {

        try {
            $res = CONNECTION->is_username_available($_POST['usernameField']);
            if (!$res) {
                $errorMsg = "<div class=\"m-3 p-3 card bg-danger\"><h3><strong>Username not available!</strong></h3></div>";
            }
            else if ($_POST['passwordField'] !== $_POST['repeatPasswordField']) {
                $errorMsg = "<div class=\"m-3 p-3 card bg-danger\"><h3><strong>Passwords do not match!</strong></h3></div>";
            }
            else {

                // Registration code
                $owners_id = CONNECTION->generateID();
                $phones = new contacts($owners_id, [$_POST['customerNumberField1'], $_POST['customerNumberField2']]);
                $house_address = new address($owners_id, $_POST['customerCountryField'], (int)$_POST['customerCityField'], $_POST['customerStateField'], $_POST['customerZipCodeField'], $_POST['customerAddressField'], $_POST['customerHoldingNumberField'], $_POST['customerNotesField']);

                $person = new customer($owners_id, $_POST['firstNameField'], $_POST['lastNameField'], new DateTime($_POST['birthDateField']), $_POST['genderRadio'], $phones, $house_address);
                $user_id = CONNECTION->generateID();
                $registering_user = new user(null, $user_id, $_POST['usernameField'], $person, null, null, null, null);

                CONNECTION->begin();    // begin transaction here

                $res = CONNECTION->create_user($registering_user, $_POST['repeatPasswordField']);
                $errorMsg = "<div class=\"m-3 p-3 card bg-danger\"><h3><strong>Proceed with the business related data tab</strong></h3></div>";
                $_SESSION['REGISTERING_USER'] = serialize($registering_user);
                $_SESSION['USER_PWD'] = $_POST['passwordField'];
                foreach ($_POST as $x) {     // unset POST VARS
                    unset($x);
                }
            }
        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    else if($_SESSION['REGISTERING_USER'] && isset($_POST['cancelButton'])) {
        unset($_SESSION['REGISTERING_USER']);
        unset($_SESSION['USER_PWD']);
    }
    else if(isset($_SESSION['REGISTERING_USER']) &&
            isset($_POST['companyNameField']) &&
            isset($_POST['companyTypeField']) &&
            isset($_POST['licenceNumberField']) &&
            isset($_POST['businessNumberField1']) &&
            isset($_POST['businessNumberField2']) &&
            isset($_POST['businessCountryField']) &&
            isset($_POST['businessCityField']) &&
            isset($_POST['businessStateField']) &&  // district
            isset($_POST['businessZipCodeField']) &&
            isset($_POST['businessAddressField']) &&    // street
            isset($_POST['businessHoldingNumberField']) &&
            isset($_POST['businessNotesField'])) {

        $registering_user = unserialize($_SESSION['REGISTERING_USER']);
            if(!($registering_user instanceof user)) {
                unset($_SESSION['REGISTERING_USER']);
                $errorMsg = "<div class=\"m-3 p-3 card bg-body-tertiary\"><h3><strong>Error, try again from the start!</strong></h3></div>";
            }

        $days = array();
        if(isset($_POST['mondayCheckBox']))     $working_days[] = $_POST['mondayCheckBox'];
        if(isset($_POST['tuesdayCheckBox']))    $working_days[] = $_POST['tuesdayCheckBox'];
        if(isset($_POST['wednesdayCheckBox']))  $working_days[] = $_POST['wednesdayCheckBox'];
        if(isset($_POST['thursdayCheckBox']))   $working_days[] = $_POST['thursdayCheckBox'];
        if(isset($_POST['fridayCheckBox']))     $working_days[] = $_POST['fridayCheckBox'];
        if(isset($_POST['saturdayCheckBox']))   $working_days[] = $_POST['saturdayCheckBox'];
        if(isset($_POST['sundayCheckBox']))     $working_days[] = $_POST['sundayCheckBox'];
        $working_days = implode(',', $days);

        try {
            $business_id = CONNECTION->generateID();
            $phones = new contacts($business_id, [$_POST['businessNumberField1'], $_POST['businessNumberField2']]);
            $working_address = new address($business_id, $_POST['businessCountryField'], (int)$_POST['businessCityField'], $_POST['businessStateField'], $_POST['businessZipCodeField'], $_POST['businessAddressField'], $_POST['businessHoldingNumberField'], $_POST['businessNotesField']);

            $company = new business($business_id, $registering_user->customer, $_POST['companyNameField'], $_POST['companyTypeField'], $_POST['licenceNumberField'], $phones, $working_address, new DateTime($_POST['openTimeField']), new DateTime($_POST['closeTimeField']), $working_days, isset($_POST['businessStatusSwitch']), null);
            $registering_user->business = $company;

            CONNECTION->begin();
            $res = CONNECTION->create_user($registering_user, $_SESSION['USER_PWD']);

            if($res) {
                CONNECTION->commit();
                unset($_SESSION['REGISTERING_USER']);
                unset($_SESSION['USER_PWD']);
                session_destroy();
                header("Location: " . relativePath(ABSOLUTE_PATHS['SUCCESSFUL_REGISTRATION']));
            }
            else {
                $errorMsg = "<div class=\"m-3 p-3 card bg-body-tertiary\"><h3><strong>Error, please try again!</strong></h3></div>";
            }

        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Business Account</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
    <script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
</head>
<!--
    List of variables:
        companyNameField
        companyTypeField
        licenceField
        countryField
        customerCityField
        customerStateField      -> district
        customerZipCodeField
        customerAddressField    -> street
        customerHoldingNumberField
        customerNoteField
        companyNameField
        companyTypeField
        licenceNumberField
        businessCountryField
        businessCityField
        businessStateField      -> district
        businessZipCodeField
        businessAddressField    -> street
        businessHoldingNumberField
        businessNoteField
        mondayCheckBox
        tuesdayCheckBox
        wednesdayCheckBox
        thursdayCheckBox
        fridayCheckBox
        saturdayCheckBox
        sundayCheckBox
        openTimeField
        closeTimeField
        businessStatusSwitch
-->
<body class="bg-body">
<?php
(include_once(relativePathSystem(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePathSystem(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");
?>

<div id="top" class="container my-5 mx-auto p-0 lato-bold" style="padding-top: 70px">
    <?php if(isset($errorMsg)) echo $errorMsg; ?>


    <form method="POST" id="mainForm">
        <input type="hidden" name="request_method" value="POST">
        <?php
            if(!isset($_SESSION['REGISTERING_USER'])) {
                echo "<div class=\"my-5 p-3 card bg-body-tertiary shadow-sm\"><h3>Enter personal details</h3></div>";
                (include_once ('registrationBusinessSectionOne.php')) or die("Failed to load component");
            }
            else {
                echo "<div class=\"my-5 p-3 card bg-body-tertiary shadow-sm\"><h3>Enter business related details</h3></div>";
                (include_once ('registrationBusinessSectionTwo.php')) or die("Failed to load component");
            }

        ?>

    </form>
</div>

<?php (include_once(relativePathSystem(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
    document.getElementById('top').scrollIntoView({behavior: 'smooth'});

</script>
</body>
</html>
