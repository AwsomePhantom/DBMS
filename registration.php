
<?php
if(isset($_SERVER['POST'])) {
    echo 'Post<br>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .row {
            margin-bottom: 5px;
        }

        hr {
            margin-bottom: 2vw;
        }
    </style>
</head>
<body>
<div class="container">
<div class="card">
    <img class="card-img-top bg-primary" src="" alt="header image" style="height: 200px">
    <form class="card-body m-5 p-5" type="post">
        <h2 class="card-title">Registration</h2>
        <p class="card-text">This is a card text</p>
        <div class="row">
            <h4>Personal details</h4>
        </div>
        <div class="row p-2">
            <div class="col-md-4">
                <label for="nameField">First Name</label>
                <input type="text" class="form-control" id="nameField" required>
            </div>
            <div class="col-md-4">
                <label for="lastNameField">Last Name</label>
                <input type="text" class="form-control" id="lastNameField" required>
            </div>
            <div class="col-md-4">
                <label for="birthDate">Last Name</label>
                <input type="date" class="form-control" id="birthDate" required>
            </div>
        </div>
        <div class="row p-2">
            <label>Gender</label>
            <div class="col-md-3">
                <input type="radio" name="sexRadio" id="mRadio" required>
                <label for="mRadio">Male</label>
            </div>
            <div class="col-md-3">
                <input type="radio" name="sexRadio" id="fRadio" required>
                <label for="fRadio">Female</label>
            </div>
            <div class="col-md-8"></div>
        </div>
        <hr>
        <div class="row">
            <h4>Contacts</h4>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="emailField">Email Address</label>
                <input type="text" class="form-control" name="emailField" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="phoneNumberField1">Phone 1</label>
                <input type="text" class="form-control" name="phoneNumberField1" required>
            </div>
            <div class="col-md-4">
                <label for="phoneNumberField2">Phone 2</label>
                <input type="text" class="form-control" name="phoneNumberField2">
            </div>
        </div>
        <hr>
        <div class="row">
            <h4>Address</h4>
        </div>
        <div class="row p-2">
            <div class="col-md-4">
                <label for="countryField">Country</label>
                <input type="text" class="form-control" name="countryField" required>
            </div>
            <div class="col-md-4">
                <label for="cityField">City</label>
                <input type="text" class="form-control" name="cityField" required>
            </div>
            <div class="col-md-4">
                <label for="districtField">District</label>
                <input type="text" class="form-control" name="districtField" required>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-md-4">
                <label for="streetField">Street</label>
                <input type="text" class="form-control" name="streetField" required>
            </div>
            <div class="col-md-4">
                <label for="streetField">Number</label>
                <input type="text" class="form-control" name="numberField" required>
            </div>
        </div>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-3"><input type="submit" class="form-control btn bg-primary" value="Register"></div>
                <div class="col-md-3"><input type="reset" class="form-control btn" value="Clear"></div>
            </div>
        </div>

    </form>
</div>
</div>
</body>

</html>