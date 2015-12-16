(function($){
    SABAI = SABAI || {};
    SABAI.GoogleMaps = SABAI.GoogleMaps || {};
    SABAI.GoogleMaps.directionMap = function (map, lat, lng, zoom, trigger, input, mode, content, icon, styles, options) {
        var gmap, destination, destinationMarker, directionsDisplay, directionsService, infoWindow, markerArray = [];
        
        if (!lat || !lng) return;

        // Instantiate a directions service.
        directionsService = new google.maps.DirectionsService();

        destination = new google.maps.LatLng(lat, lng);

        // Create a map
		gmap = new google.maps.Map($(map).get(0), {
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: destination,
            scrollwheel: options.scrollwheel || false,
            styles: styles || null,
            
        });
        
        destinationMarker = new google.maps.Marker({
            position: destination, 
            map: gmap,
            animation: google.maps.Animation.DROP,
            icon: icon || null
        });

        // Create a renderer for directions and bind it to the map.
        directionsDisplay = new google.maps.DirectionsRenderer({
            map: gmap,
            suppressMarkers: true
        })

        // Instantiate an info window to hold step text.
		infoWindow = new google.maps.InfoWindow({maxWidth: 300});
        // Show street view if no content
        if (content) {
            infoWindow.setContent(content);
            // Display destination details in info window
            google.maps.event.addListener(destinationMarker, 'click', function() {
                infoWindow.open(gmap, destinationMarker);
            });
            setTimeout(function() {
                google.maps.event.trigger(destinationMarker, 'click');
            }, 1500);
        }
        
        if ($(trigger).length && $(input).length) {
            $(trigger).click(function(){
                infoWindow.close();
                // First, clear out any existing markerArray
                // from previous calculations.
                for (var i = 0; i < markerArray.length; i++) {
                    markerArray[i].setMap(null);
                }

                // Retrieve the start and end locations and create a DirectionsRequest
                var request = {
                    origin: $(input).val(),
                    destination: destination,
                    travelMode: google.maps.TravelMode[$(mode).val()] || google.maps.TravelMode.WALKING
                };

                // Route the directions and pass the response to a
                // function to create markers for each step.
                directionsService.route(request, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        //var warnings = document.getElementById("warnings_panel");
                        //warnings.innerHTML = "" + response.routes[0].warnings + "";
                        directionsDisplay.setDirections(response);
                        showSteps(response);
                    }
                });
            });
        }

        function showSteps(directionResult) {
            // For each step, place a marker, and add the text to the marker's
            // info window. Also attach the marker to an array so we
            // can keep track of it and remove it when calculating new
            // routes.
            var myRoute = directionResult.routes[0].legs[0];

            for (var i = 0; i < myRoute.steps.length; i++) {
				var icon, title;
                if (i == 0) {
                    icon = "https://chart.googleapis.com/chart?chst=d_map_xpin_icon_withshadow&chld=pin|home|00cc00";
					title= "You're here"
                } else {
					icon = "https://chart.googleapis.com/chart?chst=d_map_pin_letter_withshadow&chld=" + i + "|0000cc|ffffff";
					title = "";
				}
                var marker = new google.maps.Marker({
                    position: myRoute.steps[i].start_point,
                    map: gmap,
                    icon: icon,
					title: title
                });
                attachInstructionText(marker, myRoute.steps[i].instructions);
                markerArray[i] = marker;
            }
        }

        function attachInstructionText(marker, text) {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(text);
                infoWindow.open(gmap, marker);
            });
        }
    }
})(jQuery);