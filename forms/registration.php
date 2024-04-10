<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Account</title>
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/forms/forms_bootstrap.html'); ?>
</head>
<!--
On successful registration, ask for business account

    List of variables:
        usernameFieldz
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
        stateField
        zipCodeField
        addressField
        holdingNumberField
        noteField
-->
<body class="bg-body">


<?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/home_components/menu.php'); ?>

<div id="top" class="container bg-body my-5 mx-auto p-0 card shadow-sm" style="padding-top: 70px">
    <div class="card-header"><h3>Customer Account Registration</h3></div>
    <div class="card-body p-5">
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
                    <div class="col">
                        <label for="repeatPasswordField">Repeat Password</label>
                        <input id="repeatPasswordField" name="repeatPasswordField" type="password" class="form-control" placeholder="Password">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
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

            <div class="row mb-3">
                <label for="noteField" class="form-label">Additional information</label>
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
</div>

<?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/home_components/footer.php'); ?>

<script>
    document.getElementById('top').scrollIntoView({behavior: 'smooth'});
    var x = document.getElementById('registerToolbarLink');
    //x.removeAttribute('data-bs-toggle');
</script>
</body>
</html>