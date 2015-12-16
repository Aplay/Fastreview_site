(function($){
    SABAI = SABAI || {};
    SABAI.GoogleMaps = SABAI.GoogleMaps || {};
    SABAI.GoogleMaps.autocomplete = function (input) {
        var $input = $(input);
        if (!$input.length) return;
        new google.maps.places.Autocomplete($input.get(0), {types: ['geocode']});
        console.log($input.get(0))
        google.maps.event.addDomListener($input.get(0), 'keydown', function(e) { 

            if (e.keyCode == 13) { 
                e.preventDefault();
            }
        });
    }
})(jQuery);