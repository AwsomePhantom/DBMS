
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

