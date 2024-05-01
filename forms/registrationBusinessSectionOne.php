    <!-- Personal details -->
<fieldset>
    <p class="lead">User Account Info</p>
    <hr class="border border-secondary">
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
    <p class="lead">Customer Info</p>
    <hr class="border border-secondary">
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
    <p class="lead">Contacts</p>
    <hr class="border border-secondary">
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

<script>
    const c_countries = document.getElementById('customerCountryField');
    const c_cities = document.getElementById('customerCityField');

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
</script>
