(function($){
    SABAI = SABAI || {};
    SABAI.Directory = SABAI.Directory || {};
    SABAI.Directory.googleMap = function (mapId, markers, updater, center, zoom, styles, options) {
        var gmap,
            marker,
            currentMarker,
            markerPosition,
            infowindow = new google.maps.InfoWindow({size: new google.maps.Size(150,50)}),
            infowindowTriggerEvent = infowindowTriggerEvent || 'hover',
            i,
            bounds = new google.maps.LatLngBounds(),
            updaterTimeout,
            isFitBounds,
            initialZoom,
            update = function () {
                if (!updater || gmap.getZoom() < 11 || !$(mapId + "-update").prop("checked")) return;
                updater.call(gmap, gmap.getCenter(), gmap.getBounds(), gmap.getZoom());
            };

        gmap = new google.maps.Map($(mapId).get(0), {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false,
            mapTypeControl: false,
            zoom: zoom,
            center: center ? new google.maps.LatLng(center[0], center[1]) : new google.maps.LatLng(40.69847, -73.95144),
            scrollwheel: options.scrollwheel || false,
            styles: styles || null
        })
        
        // Add markers
        for (i = 0; i < markers.length; i++) {
            markerPosition = new google.maps.LatLng(markers[i].lat, markers[i].lng);
            marker = new google.maps.Marker({
                position: markerPosition,
                map: gmap,
                icon: markers[i].icon || null
            });
            if (!center) bounds.extend(markerPosition);
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    if (currentMarker && currentMarker.get('id') === marker.get('id')) {
                        return;
                    }
                    // Pan to the position of marker if the marker is not visible, as well as set zoom level to initial value 
                    if (!gmap.getBounds().contains(marker.getPosition())) {
                        isFitBounds = true; // prevent updater from firing
                        gmap.panTo(marker.getPosition());
                        gmap.setZoom(initialZoom);
                        isFitBounds = null;
                    }
                    if (currentMarker) {
                        currentMarker.setAnimation(null);
                    }
                    if (markers[i].content) {
                        infowindow.setContent(markers[i].content);
                        infowindow.open(gmap, marker); 
                    } else {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    }
                    currentMarker = marker;
                }
            })(marker, i));
            if (markers[i].trigger) {
                if ($(markers[i].trigger).length) {
                    $(markers[i].trigger)[infowindowTriggerEvent]((function(marker) {
                        return function() {
                            google.maps.event.trigger(marker, 'click');
                            return false;
                        };
                    })(marker));
                }
            }
            marker.set('id', i);
        }
        
        // Update map when dragged or zoom changed
        google.maps.event.addListener(gmap, 'dragend', function () {
            updaterTimeout = setTimeout(update, 1000);
        });
        google.maps.event.addListener(gmap, 'mousedown', function () {
            if (updaterTimeout) clearTimeout(updaterTimeout);
        });
        google.maps.event.addListener(gmap, 'zoom_changed', function () {
            $(mapId + "-update").prop("disabled", gmap.getZoom() < 11);
            if (!initialZoom) {
                initialZoom = gmap.getZoom();
                return;
            }
            if (isFitBounds) {
                return;
            }
            updaterTimeout = setTimeout(update, 1000);
        });
        
        // Clear current marker on closing infowindow
        google.maps.event.addListener(infowindow,'closeclick',function() {
            currentMarker = null;
        });
          
        $(mapId + "-update").prop("disabled", gmap.getZoom() < 11).prop("checked", $.cookie("sabai_directory_map_update")).click(function(){
            if ($(this).prop("checked")) {
                $.cookie("sabai_directory_map_update", true, {expires: 7});
            } else {
                $.removeCookie("sabai_directory_map_update");
            }
        });

        if (!center) {
            isFitBounds = true;
            if (markers.length > 1) {
                gmap.fitBounds(bounds);
            } else {
                gmap.setCenter(markerPosition);
            }
            isFitBounds = null;
        }
        
        return gmap;
    }
})(jQuery);
