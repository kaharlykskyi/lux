var map;
var infowindow;

function initMap() {
    var pyrmont = {lat: 50.450418, lng: 30.523541};

    map = new google.maps.Map(document.getElementById('map'), {
        center: pyrmont,
        zoom: 14
    });

    infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    service.nearbySearch({
        location: pyrmont,
        radius: 5000,
        type: ['post_office'],
        query: 'Новая Почта'
    }, callback);
}

function callback(results, status) {
    if (status === google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    }
}

function createMarker(place) {
    var placeLoc = place.geometry.location;
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(place.name);
        infowindow.open(map, this);
    });
}

function getPlacePost(city){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode(
        {'address': $('#'+city).val()},
        function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var place = results[0].geometry.location;
                var pyrmont = {lat: place.lat(), lng: place.lng()};

                map = new google.maps.Map(document.getElementById('map'), {
                    center: pyrmont,
                    zoom: 14
                });

                infowindow = new google.maps.InfoWindow();
                var service = new google.maps.places.PlacesService(map);
                service.textSearch({
                    location: pyrmont,
                    radius: 5000,
                    query: 'Новая Почта'
                }, callback);
            }
        }
    );
}

function getPostOfice(city){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode(
        {'address': $('#'+city).val() +','+ $('#delivery_department').val()},
        function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var place = results[0].geometry.location;
                var pyrmont = {lat: place.lat(), lng: place.lng()};

                map = new google.maps.Map(document.getElementById('map'), {
                    center: pyrmont,
                    zoom: 14
                });

                infowindow = new google.maps.InfoWindow();
                var service = new google.maps.places.PlacesService(map);
                service.textSearch({
                    location: pyrmont,
                    radius: 500,
                    query: 'Новая Почта,' + $('#delivery_department').val()
                }, callback);
            }
        }
    );
}