<!DOCTYPE html>
<html lang="en" data-bs-theme="blue">
<!-- add themes using the <html data-bs-theme="dark"> property -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Business Account</title>

    <link rel="stylesheet" href="../precompiled/superhero/bootstrap-color.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<!--
    List of variables:
        companyNameField
        companyTypeField
        licenceField
        countryField
        cityField
        stateField
        zipCodeField
        addressField
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
<body>
<div class="container-fluid p-2">
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
                <button class="btn btn-lg btn-secondary" type="button"><i class="bi bi-shuffle"> Sample Data</i></button>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <button class="btn btn-lg btn-primary" type="button"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
            </div>
        </div>
    </form>
</div>


</body>
</html>
