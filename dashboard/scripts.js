
var jsonArray = null;
const fetchLocationName = async (lat, lng) => {
    await fetch(
        'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + lat + '&longitude=' + lng + '&localityLanguage=en',
    )
        .then((response) => response.json())
        .then((responseJson) => {
            jsonArray = JSON.stringify(responseJson);
        });
};

function getLocation() {
    document.getElementById('currentLocation').innerHTML = "Finding location...";
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        document.getElementById('currentLocation').innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        jsonArray = JSON.parse(this.responseText);
        document.getElementById('currentLocation').innerHTML = "[" + position.coords.latitude +
            ", " + position.coords.longitude + "] " + jsonArray['locality'] + " - " + jsonArray['principalSubdivision'] +
            ", " + jsonArray['countryName'];
    }
    // https://www.bigdatacloud.com/ API
    xhttp.open("GET", 'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + position.coords.latitude + '&longitude=' + position.coords.longitude + '&localityLanguage=en');
    xhttp.send();
}



