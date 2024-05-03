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
(include (relativePathSystem(ABSOLUTE_PATHS['SITE_HEADERS']))) or die("Headers file not found");    // Needed to update COOKIES for the menu bar

    use classes\contacts;
    use classes\address;
    use classes\customer;
    use classes\user;

define("REGISTRATION_POST_URI", getURI());  // to clean the url from variables
$errorMsg = null;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $errorMsg;
    if( isset($_POST['usernameField']) &&
        isset($_POST['passwordField']) &&
        isset($_POST['repeatPasswordField']) &&
        isset($_POST['emailField']) &&
        isset($_POST['firstNameField']) &&
        isset($_POST['lastNameField']) &&
        isset($_POST['birthDateField']) &&
        isset($_POST['genderRadio']) &&
        isset($_POST['numberField1']) &&
        isset($_POST['numberField2']) &&
        isset($_POST['countryField']) &&
        isset($_POST['cityField']) &&
        isset($_POST['stateField']) &&  // district
        isset($_POST['zipCodeField']) &&
        isset($_POST['addressField']) &&    // street
        isset($_POST['holdingNumberField']) &&
        isset($_POST['notesField'])) {
            try {
                $res = CONNECTION->is_username_available($_POST['usernameField']);
                if (!$res) {
                    $errorMsg = "<h3>Username not available!</h3>";
                }
                else if ($_POST['passwordField'] !== $_POST['repeatPasswordField']) {
                    $errorMsg = "<h3>Passwords do not match!</h3>";
                }
                else {
                    $unique_id = CONNECTION->generateID();
                    $phones = new contacts($unique_id, [$_POST['numberField1'], $_POST['numberField2']]);
                    $house_address = new address($unique_id, $_POST['countryField'], (int)$_POST['cityField'], $_POST['stateField'], $_POST['zipCodeField'], $_POST['addressField'], $_POST['holdingNumberField'], $_POST['notesField']);

                    $person = new customer($unique_id, $_POST['firstNameField'], $_POST['lastNameField'], new DateTime($_POST['birthDateField']), $_POST['genderRadio'], $phones, $house_address);
                    $unique_id = CONNECTION->generateID();
                    $user = new user(null, $unique_id, $_POST['usernameField'], $_POST['emailField'], $person, null, null, null, null);

                    CONNECTION->begin();
                    $res = CONNECTION->create_user($user, $_POST['repeatPasswordField']);
                    sleep(1);
                    if($res) {
                        CONNECTION->commit();
                        header("Location: " . relativePathSystem(ABSOLUTE_PATHS['SUCCESSFUL_REGISTRATION']));
                    }
                    else {
                        //throw new Exception("User Registration failed");
                        $errorMsg = "Registration failed";
                    }
                }
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        unset($_POST['passwordField']);
        unset($_POST['repeatPasswordField']);
        //header("Location: " . REGISTRATION_POST_URI);
    }
}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Account</title>
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_STYLESHEET']); ?>">
</head>
<!--
On successful registration, ask for business account

    List of variables:
        usernameField
        passwordField
        repeatPasswordField
        emailField
        firstNameField
        lastNameField
        birthDateField
        genderRadio
        numberField1
        numberField2
        countryField
        cityField
        stateField      -> district
        zipCodeField
        addressField    -> street
        holdingNumberField
        notesField
-->
<body class="bg-body">


<?php
(include_once(relativePathSystem(ABSOLUTE_PATHS['LOADING_PAGE']))) or die("Failed to load component");
(include_once(relativePathSystem(ABSOLUTE_PATHS['MENU_PAGE'])))  or die("Failed to load component");

?>

<div id="top" class="container bg-body my-5 mx-auto p-0 card shadow-sm lato-regular" style="padding-top: 70px;">
    <div class="card-header"><span class="card-title">Customer Account Registration</span></div>
    <div class="card-body p-5">
    <form method="POST">
            <fieldset>
                <p class="lead">User Account Info</p>
                <hr class="border border-secondary">
                <div class="row mb-3">
                    <div class="col">
                        <label for="usernameField">Username</label>
                        <input id="usernameField" name="usernameField" type="text" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col">
                        <label for="passwordField">Password</label>
                        <input id="passwordField" name="passwordField" type="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="repeatPasswordField">Repeat Password</label>
                        <input id="repeatPasswordField" name="repeatPasswordField" type="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="emailField">Email Address</label>
                        <div class="input-group mb-3">
                            <input id="emailField" name="emailField" type="text" class="form-control" placeholder="user@domain.com" aria-label="Username" aria-describedby="basic-addon1" required>
                            <span class="input-group-text" id="basic-addon1">@</span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <p class="lead">Customer Info</p>
                <hr class="border border-secondary">
                <div class="row mb-3">
                    <div class="col">
                        <label for="firstNameField">First Name</label>
                        <input id="firstNameField" name="firstNameField" type="text" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="col">
                        <label for="lastNameField">Last Name</label>
                        <input id="lastNameField" name="lastNameField" type="text" class="form-control" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="birthDateField">Birth Date</label>
                        <input id="birthDateField" name="birthDateField" type="date" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Gender</label>
                        <div class="form-check">
                            <input id="genderRadio" name="genderRadio" class="form-check-input" type="radio" value="M" checked>
                            <label class="form-check-label" for="genderRadio">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input id="genderRadio2" name="genderRadio" class="form-check-input" value="F" type="radio">
                            <label class="form-check-label" for="genderRadio2">
                                Female
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>

        <fieldset>
            <p class="lead">Contacts</p>
            <hr class="border border-secondary">
            <div class="row mb-3">
                <div class="col">
                    <label for="numberField1">Phone</label>
                    <input id="numberField1" name="numberField1" class="form-control" type="text" placeholder="533-444-652-8686" required>
                </div>
                <div class="col">
                    <label for="numberField2">Mobile</label>
                    <input id="numberField2" name="numberField2" class="form-control" type="text" placeholder="888-455-8470">
                </div>
            </div>
        </fieldset>

        <fieldset>
        <p class="lead">Address</p>
            <hr class="border border-secondary">
            <div class="row mb-3">
                <div class="col">
                    <label for="countryField">Country</label>
                    <!-- <input id="countryField" name="countryField" type="text" class="form-control"> -->
                    <select id="countryField" name="countryField" class="form-control form-select form-select-lg mb-3" aria-label="Countries" required>
                        <option selected>Select a country</option>
                        <?php
                                $countries = CONNECTION->getCountries();
                                foreach ($countries as $country) {
                                    echo "<option value=\"{$country['code']}\">{$country['name']}</option>";
                                }
                        ?>

                    </select>
                </div>
                <div class="col">
                    <label for="cityField">City</label>
                    <!-- <input id="cityField" name="cityField" type="text" class="form-control"> -->
                    <select id="cityField" name="cityField" class="form-control form-select form-select-lg mb-3" aria-label="Cities" required>
                        <option selected>Select a city</option>
                        <option value="" selected>Select a country first</option>
                    </select>
                </div>
                <div class="col">
                    <label for="stateField">State / District</label>
                    <input id="stateField" name="stateField" type="text" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <label for="zipCodeField">Zip Code</label>
                    <input id="zipCodeField" name="zipCodeField" type="number" class="form-control" placeholder="120XXX" required>
                </div>
                <div class="col-6">
                    <label for="addressField">Address</label>
                    <input id="addressField" name="addressField" type="text" class="form-control" placeholder="Street" required>
                </div>
                <div class="col-2">
                    <label for="holdingNumberField">Number</label>
                    <input id="holdingNumberField" name="holdingNumberField" type="text" class="form-control" placeholder="13 INT A" required>
                </div>
            </div>

            <div class="row mb-3 mx-1">
                <label for="noteField" class="form-label">Additional information</label>
                <textarea id="noteField" name="notesField" class="form-control"  rows="3" placeholder="Notes"></textarea>
            </div>
        </fieldset>
        <hr>
        <div class="row mb-3">
            <div class="col-6 text-start">
                <button class="btn btn-lg btn-dark" type="button"><i class="bi bi-shuffle"> Sample Data</i></button>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <button class="btn btn-lg btn-primary" type="submit"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
            </div>
        </div>
    </form>
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
<?php (include_once(relativePathSystem(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
    document.getElementById('top').scrollIntoView({behavior: 'smooth'});

    const countries = document.getElementById('countryField');
    const cities = document.getElementById('cityField');
    countries.addEventListener('change', function () {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    let array = JSON.parse(this.responseText);
                    while(cities.children.length > 0) {
                        cities.removeChild(cities.lastChild);
                    }

                    for (let x of array) {
                        let option = document.createElement("option");
                        option.value = x['id'];
                        option.text = x['name'];
                        cities.appendChild(option);
                    }
                }
                catch (exp) {
                    console.log(exp);
                }
            }
        }
        let countryCode = countries.value;
        xmlhttp.open("GET", '<?php echo relativePath(ABSOLUTE_PATHS['COUNTRIES']); ?>' + '?countryCode=' + countryCode, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send();
    });
</script>
<script src="<?php echo relativePath(ABSOLUTE_PATHS['GLOBAL_SCRIPT']); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
