<!DOCTYPE html>
<html>

<head>
    <title>Exibir Rota no Google Maps</title>

    <script>
        function initMap() {
        let map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -22.9068,
                lng: -43.1729
            }, // Coordenadas iniciais (Rio de Janeiro, Brasil)
            zoom: 12 // Zoom inicial
        });


        let directionsService = new google.maps.DirectionsService();

        let directionsDisplay = new google.maps.DirectionsRenderer({
            preserveViewport: true
        });

        directionsDisplay.setPanel(document.getElementById("sidebar"));

        // console.log(directionsService, directionsDisplay)


        // Obter localização atual do usuário
        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(function(position) {

                var userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // Atualizar o centro do mapa para a localização do usuário
                map.setCenter(userLocation);

                // https://developers.google.com/maps/documentation/javascript/directions?hl=pt-br
                directionsDisplay.setMap(map);

                var request = {
                    origin: userLocation, // Ponto de partida

                    destination: {
                        lat: -23.5349528,
                        lng: -46.7034393
                    },

                    travelMode: 'DRIVING' // Modo de viagem (DRIVING, WALKING, BICYCLING, etc.)
                };

                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        console.log(result);


                        var t =  directionsDisplay.setDirections(result);


                        // console.log(t,directionsDisplay)
                    }
                });
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }
    </script>
</head>

<body>
    <div id="map" style="height: 900px;"></div>
    <div id="sidebar"></div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAFX6ylTq5sDdTT1Eiw9x1DhUlMRkn7Qo&callback=initMap"></script>
</body>

</html>