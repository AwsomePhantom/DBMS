<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/site_variables.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Business Account</title>
    <?php echo ABSOLUTE_PATHS['FULL_BOOTSTRAP']; ?>
</head>
<!--
    List of variables:
        companyNameField
        companyTypeField
        licenceField
        countryField
        cityField
        stateField      -> district
        zipCodeField
        addressField    -> street
        holdingNumberField
        noteField
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
include($_SERVER['DOCUMENT_ROOT'] . '/home_components/loading.php');
include(ABSOLUTE_PATHS['MENU_PAGE']);
?>

<div id="top" class="container bg-body my-5 mx-auto p-0 lato-bold" style="padding-top: 70px">
    <div class="card bg-body-tertiary mb-3 p-2 border-primary align-middle" style="border-color:lightsalmon;">
        <span class="h4">Please fill the form</span>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="one-tab" data-bs-toggle="tab" data-bs-target="#personalDetailsTab" type="button" role="tab" aria-controls="One" aria-selected="true">Personal details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="two-tab" data-bs-toggle="tab" data-bs-target="#businessDetailsTab" type="button" role="tab" aria-controls="Two" aria-selected="false">Business profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="three-tab" data-bs-toggle="tab" data-bs-target="#proceedTab" type="button" role="tab" aria-controls="Three" aria-selected="false">Proceed</button>
        </li>
    </ul>

    <div class="tab-content border p-3 shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="personalDetailsTab" role="tabpanel" aria-labelledby="Personal Details">
        <!-- Personal details -->
            <form>
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
                                <input id="genderRadio" name="genderRadio" class="form-check-input" type="radio" checked>
                                <label class="form-check-label" for="genderRadio">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input id="genderRadio2" name="genderRadio" class="form-check-input" type="radio">
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
                            <label for="numberField1">Phone</label>
                            <input id="numberField1" name="numberField1" class="form-control" type="text" placeholder="533-444-652-8686">
                        </div>
                        <div class="col">
                            <label for="numberField2">Mobile</label>
                            <input id="numberField2" name="numberField2" class="form-control" type="text" placeholder="888-455-8470">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Address</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="countryField">Country</label>
                            <input id="countryField" name="countryField" type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="cityField">City</label>
                            <input id="cityField" name="cityField" type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="stateField">State / District</label>
                            <input id="stateField" name="stateField" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="zipCodeField">Zip Code</label>
                            <input id="zipCodeField" name="zipCodeField" type="number" class="form-control" placeholder="120XXX">
                        </div>
                        <div class="col-6">
                            <label for="addressField">Address</label>
                            <input id="addressField" name="addressField" type="text" class="form-control" placeholder="Street">
                        </div>
                        <div class="col-2">
                            <label for="holdingNumberField">Number</label>
                            <input id="holdingNumberField" name="holdingNumberField" type="text" class="form-control" placeholder="13 INT A">
                        </div>
                    </div>

                    <div class="row mb-3 mx-1">
                        <label for="noteField" class="form-label">Additional information (House Address)</label>
                        <textarea id="noteField" name="noteField" class="form-control"  rows="3" placeholder="Notes"></textarea>
                    </div>

                </fieldset>
                <hr>
                <div class="row mb-3">
                    <div class="col-6 text-start">
                        <button class="btn btn-lg btn-dark" type="button"><i class="bi bi-shuffle"> Sample Data</i></button>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-lg btn-primary" type="button"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="businessDetailsTab" role="tabpanel" aria-labelledby="Two">
            <!-- Office information -->

            <form>
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
                            <label for="licenceField">Licence Number</label>
                            <input id="licenceField" name="licenceField" type="text" class="form-control" placeholder="TIN 1415-6514-4448-XXXX">
                        </div>
                    </div>

                </fieldset>

                <fieldset>
                    <legend>Company Address</legend>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="countryField">Country</label>
                            <input id="countryField" name="countryField" type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="cityField">City</label>
                            <input id="cityField" name="cityField" type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="stateField">State</label>
                            <input id="stateField" name="stateField" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="zipCodeField">Zip Code</label>
                            <input id="zipCodeField" name="zipCodeField" type="number" class="form-control" placeholder="120XXX">
                        </div>
                        <div class="col-6">
                            <label for="addressField">Address</label>
                            <input id="addressField" name="addressField" type="text" class="form-control" placeholder="Street">
                        </div>
                        <div class="col-2">
                            <label for="holdingNumberField">Number</label>
                            <input id="holdingNumberField" name="holdingNumberField" type="text" class="form-control" placeholder="13 INT A">
                        </div>
                    </div>

                    <div class="row mb-3 mx-1">
                        <label for="noteField" class="form-label">Additional information</label>
                        <textarea id="noteField" name="noteField" class="form-control"  rows="3" placeholder="Notes"></textarea>
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
                                <input class="form-check-input" type="checkbox" role="switch" id="businessStatusSwitch">
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
                    <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-lg btn-primary" type="button"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="proceedTab" role="tabpanel" aria-labelledby="Finish">
            ...
        </div>

    </div>
</div>

<?php include ABSOLUTE_PATHS['FOOTER_PAGE']; ?>
<script>
    document.getElementById('loader').classList.add('fadeout');
    document.getElementById('top').scrollIntoView({behavior: 'smooth'});
</script>
</body>
</html>
