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
    <div class="col-6 justify-content-end align-items-end">
        <button value="true" name="cancelButton" class="btn btn-lg btn-outline-primary" type="submit"><i class="bi bi-x-square-fill"> Cancel</i></button>
        <button value="true" name="businessInfoSubmitButton" class="btn btn-lg btn-primary" type="submit"><i class="bi bi-arrow-right-circle-fill"> Proceed</i></button>
    </div>
</div>

<script>
    const b_countries = document.getElementById('businessCountryField');
    const b_cities = document.getElementById('businessCityField');

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
