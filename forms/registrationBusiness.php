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

$registering_user = null;
$alertMessage = 'Hello, World';
$script = null;

define("REGISTRATION_POST_URI", getURI());

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if( $_POST['personalInfoSubmitButton'] === 'true' &&
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
        var_dump($_POST);
        try {
            $res = CONNECTION->is_username_available($_POST['usernameField']);
            if (!$res) {
                throw new Exception("Username not available");
            }

            if ($_POST['passwordField'] !== $_POST['repeatPasswordField']) {
                throw new Exception("Passwords do not match");
            }

            // Registration code
            $owners_id = CONNECTION->generateID();
            $phones = new contacts($owners_id, [$_POST['customerNumberField1'], $_POST['customerNumberField2']]);
            $house_address = new address($owners_id, $_POST['customerCountryField'], (int)$_POST['customerCityField'], $_POST['customerStateField'], $_POST['customerZipCodeField'], $_POST['customerAddressField'], $_POST['customerHoldingNumberField'], $_POST['customerNotesField']);

            $person = new customer($owners_id, $_POST['firstNameField'], $_POST['lastNameField'], new DateTime($_POST['birthDateField']), $_POST['genderRadio'], $phones, $house_address);
            $user_id = CONNECTION->generateID();
            global $registering_user;
            $registering_user = new user(null, $user_id, $_POST['usernameField'], $person, null, null, null, null);

            CONNECTION->begin();    // begin transaction here

            $res = CONNECTION->create_user($registering_user, $_POST['repeatPasswordField']);
            echo 'Successful registration'; ////////////////////
            $_SESSION['REGISTERING_USER'] = serialize($registering_user);
            foreach($_POST as $x) {     // unset POST VARS
                unset($x);
            }

        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    else if(isset($_SESSION['REGISTERING_USER_INFO']) &&
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
                throw new Exception("User class object corrupted");
            }

        $week = array('MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN');
        $working_days = array();
        if(isset($_POST['mondayCheckBox'])) $working_days[] = $week[0];
        if(isset($_POST['tuesdayCheckBox'])) $working_days[] = $week[1];
        if(isset($_POST['wednesdayCheckBox'])) $working_days[] = $week[2];
        if(isset($_POST['thursdayCheckBox'])) $working_days[] = $week[3];
        if(isset($_POST['fridayCheckBox'])) $working_days[] = $week[4];
        if(isset($_POST['saturdayCheckBox'])) $working_days[] = $week[5];
        if(isset($_POST['sundayCheckBox'])) $working_days[] = $week[6];
        $working_days = implode(',', $working_days);

        try {
            $business_id = CONNECTION->generateID();
            $phones = new contacts($business_id, [$_POST['businessNumberField1'], $_POST['businessNumberField2']]);
            $working_address = new address($business_id, $_POST['businessCountryField'], (int)$_POST['businessCityField'], $_POST['businessStateField'], $_POST['businessZipCodeField'], $_POST['businessAddressField'], $_POST['businessHoldingNumberField'], $_POST['businessNotesField']);

            $company = new business($business_id, $registering_user->customer, $_POST['companyNameField'], $_POST['companyTypeField'], $_POST['licenceNumberField'], $phones, $working_address, new DateTime($_POST['openTimeField']), new DateTime($_POST['closeTimeField']), $working_days, null, $_POST['businessStatusSwitch']);
            $res = CONNECTION->register_business($company);
            $registering_user->business = $company;
            $res = CONNECTION->addBusinessToUser($registering_user);

            if($res) {
                CONNECTION->commit();
                unset($_SESSION['REGISTERING_USER']);
                header("Location: " . relativePath(ABSOLUTE_PATHS['SUCCESSFUL_REGISTRATION']));
            }
            else {
                header("Location: " . REGISTRATION_POST_URI);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4!== $separatorLISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/styles/styles.css">
    <script src="/scripts/main.js"></script>
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
(include_once('../home_components/loading.php')) or die("Failed to load component");
(include_once('../home_components/menu.php'))  or die("Failed to load component");
?>

<div id="top" class="container my-5 mx-auto p-0 lato-bold" style="padding-top: 70px">
    <div class="card bg-body-tertiary mb-3 p-2 border-primary align-middle" style="border-color:lightsalmon;">
        <span class="h4">Please fill the form</span>
    </div>
    <ul class="nav nav-tabs lato-regular" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="one-tab" style="width: 200px" data-bs-toggle="tab" data-bs-target="#personalDetailsTab" type="button" role="tab" aria-controls="One" aria-selected="true">Personal details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="two-tab" style="width: 200px" data-bs-toggle="tab" data-bs-target="#businessDetailsTab" type="button" role="tab" aria-controls="Two" aria-selected="false">Business profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="three-tab" style="width: 200px" data-bs-toggle="tab" data-bs-target="#proceedTab" type="button" role="tab" aria-controls="Three" aria-selected="false">Proceed</button>
        </li>
    </ul>

    <form method="POST" id="mainForm">
        <div class="tab-content border p-3 shadow-sm" id="myTabContent">

            <div class="tab-pane fade show active" id="personalDetailsTab" role="tabpanel" aria-labelledby="Personal Details">
            <!-- Personal details -->
                <fieldset>
                    <legend>User Account Info</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="usernameField">Username</label>
                            <input id="usernameField" name="usernameField" type="text" class="form-control" placeholder="Username">
                        </div>
                        <div class="col">
                            <label for="passwordField">Password</label>
                            <input id="passwordField" name="passwordField" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="repeatPasswordField">Repeat Password</label>
                            <input id="repeatPasswordField" name="repeatPasswordField" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="emailField">Email Address</label>
                            <div class="input-group mb-3">
                                <input id="emailField" name="emailField" type="text" class="form-control" placeholder="user@domain.com" aria-label="Username" aria-describedby="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">@</span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Customer Info</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstNameField">First Name</label>
                            <input id="firstNameField" name="firstNameField" type="text" class="form-control" placeholder="First Name">
                        </div>
                        <div class="col">
                            <label for="lastNameField">Last Name</label>
                            <input id="lastNameField" name="lastNameField" type="text" class="form-control" placeholder="Last Name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="birthDateField">Birth Date</label>
                            <input id="birthDateField" name="birthDateField" type="date" class="form-control">
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
                                <input id="genderRadio" name="genderRadio" class="form-check-input" type="radio" value="F">
                                <label class="form-check-label" for="genderRadio2">
                                    Female
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contacts</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="customerNumberField1">Phone</label>
                            <input id="customerNumberField1" name="customerNumberField1" class="form-control" type="text" placeholder="533-444-652-8686">
                        </div>
                        <div class="col">
                            <label for="customerNumberField2">Mobile</label>
                            <input id="customerNumberField2" name="customerNumberField2" class="form-control" type="text" placeholder="888-455-8470">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Address</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="customerCountryField">Country</label>
                            <!-- <input id="countryField" name="countryField" type="text" class="form-control"> -->
                            <select id="customerCountryField" name="customerCountryField" class="form-control form-select form-select-lg mb-3" aria-label="Countries">
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
                            <label for="customerCityField">City</label>
                            <!-- <input id="cityField" name="cityField" type="text" class="form-control"> -->
                            <select id="customerCityField" name="customerCityField" class="form-control form-select form-select-lg mb-3" aria-label="Cities">
                                <option selected>Select a city</option>
                                <option value="0" selected>Select a country first</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="customerStateField">State / District</label>
                            <input id="customerStateField" name="customerStateField" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="customerZipCodeField">Zip Code</label>
                            <input id="customerZipCodeField" name="customerZipCodeField" type="number" class="form-control" placeholder="120XXX">
                        </div>
                        <div class="col-6">
                            <label for="customerAddressField">Address</label>
                            <input id="customerAddressField" name="customerAddressField" type="text" class="form-control" placeholder="Street">
                        </div>
                        <div class="col-2">
                            <label for="customerHoldingNumberField">Number</label>
                            <input id="customerHoldingNumberField" name="customerHoldingNumberField" type="text" class="form-control" placeholder="13 INT A">
                        </div>
                    </div>

                    <div class="row mb-3 mx-1">
                        <label for="customerNotesField" class="form-label">Additional information (House Address)</label>
                        <textarea id="customerNotesField" name="customerNotesField" class="form-control"  rows="3" placeholder="Notes"></textarea>
                    </div>

                </fieldset>
                <hr>
                <div class="row mb-3">
                    <div class="col-6 text-start">
                        <button class="btn btn-lg btn-dark" type="button" onclick="sample2()"><i class="bi bi-shuffle"> Sample Data</i></button>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button value="true" name="personalInfoSubmitButton" class="btn btn-lg btn-primary" type="submit"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="businessDetailsTab" role="tabpanel" aria-labelledby="Two">
            <!-- Office information -->

                <fieldset>
                    <legend>Company Info</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="companyNameField">Company Name</label>
                            <input id="companyNameField" name="companyNameField" type="text" class="form-control" placeholder="Company Name">
                        </div>
                        <div class="col">
                            <label for="companyTypeField">Company Type</label>
                            <input id="companyTypeField" name="companyTypeField" type="text" class="form-control" placeholder="Types">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="licenceNumberField">Licence Number</label>
                            <input id="licenceNumberField" name="licenceNumberField" type="text" class="form-control" placeholder="TIN 1415-6514-4448-XXXX">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contacts</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="businessNumberField1">Phone</label>
                            <input id="businessNumberField1" name="businessNumberField1" class="form-control" type="text" placeholder="533-444-652-8686">
                        </div>
                        <div class="col">
                            <label for="businessNumberField2">Mobile</label>
                            <input id="businessNumberField2" name="businessNumberField2" class="form-control" type="text" placeholder="888-455-8470">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Company Address</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="businessCountryField">Country</label>
                            <!-- <input id="countryField" name="countryField" type="text" class="form-control"> -->
                            <select id="businessCountryField" name="businessCountryField" class="form-control form-select form-select-lg mb-3" aria-label="Countries">
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
                            <label for="businessCityField">City</label>
                            <!-- <input id="cityField" name="cityField" type="text" class="form-control"> -->
                            <select id="businessCityField" name="businessCityField" class="form-control form-select form-select-lg mb-3" aria-label="Cities">
                                <option selected>Select a city</option>
                                <option value="0" selected>Select a country first</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="businessStateField">State/District</label>
                            <input id="businessStateField" name="businessStateField" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="businessZipCodeField">Zip Code</label>
                            <input id="businessZipCodeField" name="businessZipCodeField" type="number" class="form-control" placeholder="120XXX">
                        </div>
                        <div class="col-6">
                            <label for="businessAddressField">Address</label>
                            <input id="businessAddressField" name="businessAddressField" type="text" class="form-control" placeholder="Street">
                        </div>
                        <div class="col-2">
                            <label for="businessHoldingNumberField">Number</label>
                            <input id="businessHoldingNumberField" name="businessHoldingNumberField" type="text" class="form-control" placeholder="13 INT A">
                        </div>
                    </div>

                    <div class="row mb-3 mx-1">
                        <label for="businessNotesField" class="form-label">Additional information</label>
                        <textarea id="businessNotesField" name="businessNotesField" class="form-control"  rows="3" placeholder="Notes"></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Office Hours</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Office Weekdays</label>
                            <div class="pl-4" style="word-spacing: 10px">
                                <input class="form-check-input" type="checkbox" value="MON" id="mondayCheckBox" checked>
                                <label class="form-check-label" for="mondayCheckBox">MON&nbsp</label>
                                    <input class="form-check-input" type="checkbox" value="TUE" id="tuesdayCheckBox" checked>
                                    <label class="form-check-label" for="tuesdayCheckBox">TUE&nbsp</label>
                                        <input class="form-check-input" type="checkbox" value="WED" id="wednesdayCheckBox" checked>
                                        <label class="form-check-label" for="wednesdayCheckBox">WED&nbsp</label>
                                            <input class="form-check-input" type="checkbox" value="THU" id="thursdayCheckBox" checked>
                                            <label class="form-check-label" for="thursdayCheckBox">THU&nbsp</label>
                                                <input class="form-check-input" type="checkbox" value="FRI" id="fridayCheckBox" checked>
                                                <label class="form-check-label" for="fridayCheckBox">FRI&nbsp</label>
                                                    <input class="form-check-input" type="checkbox" value="SAT" id="saturdayCheckBox">
                                                    <label class="form-check-label" for="saturdayCheckBox">SAT&nbsp</label>
                                                        <input class="form-check-input" type="checkbox" value="SUN" id="sundayCheckBox">
                                                        <label class="form-check-label" for="sundayCheckBox">SUN&nbsp</label>

                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="openTimeField">Open</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-clock"></i></span>
                                <input id="openTimeField" class="form-control" name="openTimeField" type="time" value="08:00:00">
                            </div>
                        </div>
                        <div class="col">
                            <label for="closeTimeField">Close</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-clock"></i></span>
                                <input id="closeTimeField" class="form-control" name="closeTimeField" type="time" value="20:00:00">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-check form-switch">
                                <input id="businessStatusSwitch" name="businessStatusSwitch" value="true" class="form-check-input" type="checkbox" role="switch">
                                <label class="form-check-label" for="businessStatusSwitch">The business activity is open and running</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <div class="row mb-3 align-items-center">
                    <div class="col-6 text-start">
                        <button class="btn btn-lg btn-dark" type="button"><i class="bi bi-shuffle"> Sample Data</i></button>
                    </div>
                    <div class="col-6 justify-content-end">
                        <button value="true" name="businessInfoSubmitButton" class="btn btn-lg btn-primary" type="submit"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="proceedTab" role="tabpanel" aria-labelledby="Finish">
                ...
            </div>
        </div>
    </form>
</div>

<?php (include_once(relativePath(ABSOLUTE_PATHS['FOOTER_PAGE']))) or die("Failed to load component"); ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
    document.getElementById('top').scrollIntoView({behavior: 'smooth'});

    const c_countries = document.getElementById('customerCountryField');
    const c_cities = document.getElementById('customerCityField');
    const b_countries = document.getElementById('businessCountryField');
    const b_cities = document.getElementById('businessCityField');

    let c_xmlhttp = new XMLHttpRequest();
    c_countries.addEventListener('change', function () {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    let array = JSON.parse(this.responseText);
                    while(c_cities.children.length > 0) {
                        c_cities.removeChild(c_cities.lastChild);
                    }

                    for (let x of array) {
                        let option = document.createElement("option");
                        option.value = x['id'];
                        option.text = x['name'];
                        c_cities.appendChild(option);
                    }
                }
                catch (exp) {
                    console.log(exp);
                }
            }
        }
        let countryCode = c_countries.value;
        xmlhttp.open("GET", '<?php echo relativePath(ABSOLUTE_PATHS['COUNTRIES']); ?>' + '?countryCode=' + countryCode, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send();
    });

    b_countries.addEventListener('change', function () {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    let array = JSON.parse(this.responseText);
                    while(b_cities.children.length > 0) {
                        b_cities.removeChild(b_cities.lastChild);
                    }

                    for (let x of array) {
                        let option = document.createElement("option");
                        option.value = x['id'];
                        option.text = x['name'];
                        b_cities.appendChild(option);
                    }
                }
                catch (exp) {
                    console.log(exp);
                }
            }
        }
        let countryCode = b_countries.value;
        xmlhttp.open("GET", '<?php echo relativePath(ABSOLUTE_PATHS['COUNTRIES']); ?>' + '?countryCode=' + countryCode, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/json');
        xmlhttp.send();
    });

</script>
</body>
</html>
