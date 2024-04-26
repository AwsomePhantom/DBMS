
function toggleMenu() {
    let size = getViewport();
    if(size === 'xs' || size === 'sm' || size === 'md') {
        const menuToggle = document.getElementById('navbarSupportedContent');
        if(menuToggle.classList.contains('collapse')) {
            menuToggle.classList.toggle('collapse');
        }
    }
}

function getViewport () {
    // https://stackoverflow.com/a/8876069
    const width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        if (width <= 576) return 'xs';
        if (width <= 768) return 'sm';
        if (width <= 992) return 'md';
        if (width <= 1200) return 'lg';
        if (width <= 1400) return 'xl';
        return 'xxl';
}

function populateCountries() {

/*
    const countryField = document.querySelector('#countryField');
    const cityField = document.querySelector('#cityField');

    fetch('../database/countries.json')
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.code;
                option.text = item.name;

                countryField.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching JSON:', error);
        });

    countryField.addEventListener('change', function() {
        let child = cityField.lastElementChild;
        while (child) {
            cityField.removeChild(child);
            child = cityField.lastElementChild;
        }

        fetch('../database/cities.json')
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = item.name;

                    cityField.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching JSON:', error);
            });

    });*/
}

function sample2() {
    let arr = [
        ['rabinul', '1234', 'rabinul@email.com', 'rabinul', 'islam', '1992-11-10', 'M', '8845-545-485', '4771-466-158', 18, 5, 'Dhaka', '1204', 'Street', '15', 'none'],
        ['bob', '1234', 'bob@email.com', 'bob', 'white', '1987-04-05', 'M', '9594-4848-4848', '1511-1811-1816', 17, 5, 'Stete', '1204', 'Street 2', '17', 'none'],
    ];
    let index = 1;
    document.getElementById('usernameField').value = arr[index][0];
    document.getElementById('passwordField').value = arr[index][1];
    document.getElementById('repeatPasswordField').value = arr[index][1];
    document.getElementById('emailField').value = arr[index][2];
    document.getElementById('firstNameField').value = arr[index][3];
    document.getElementById('lastNameField').value = arr[index][4];
    document.getElementById('birthDateField').value = arr[index][5];
    document.getElementById('genderRadio').value = arr[index][6];
    document.getElementById('customerNumberField1').value = arr[index][7];
    document.getElementById('customerNumberField1').value = arr[index][8];
    document.getElementById('customerCountryField').selectedIndex = arr[index][9];
    setTimeout(1000);
    document.getElementById('customerCityField').selectedIndex = arr[index][10];
    document.getElementById('customerStateField').value = arr[index][11];
    document.getElementById('customerZipCodeField').value = arr[index][12];
    document.getElementById('customerAddressField').value = arr[index][13];
    document.getElementById('customerHoldingNumberField').value = arr[index][14];
    document.getElementById('customerNotesField').innerText = arr[index][15];

}