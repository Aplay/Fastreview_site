<script type="text/javascript">
function LoadGoogle()
    {
        if(typeof google != 'undefined' && google && google.load)
        {
                google.load("maps", "3", {other_params:"sensor=false&libraries=places&language=ru", callback:function() {

			    $LAB.script("/themes/material/js/plugins/sabai129/sabai-directory/assets/js/sabai-googlemaps-directionmap.js")
			        .script("/themes/material/js/plugins/sabai129/sabai-directory/assets/js/sabai-googlemaps-autocomplete.js").wait(function(){
			            SABAI.GoogleMaps.directionMap(
			                "#sabai-directory-map",
			                52.542156,
			                39.591303,
			                "#sabai-directory-map-direction-search .sabai-directory-search-btn button",
			                "#sabai-directory-map-direction-search .sabai-directory-direction-location input",
			                "#sabai-directory-map-direction-search .sabai-directory-direction-mode select",
			                '<div class=\"sabai-directory-listing-infobox sabai-directory-listing-infobox-noimage sabai-clearfix\"><\/div>',
			                '#sabai-directory-map-direction-panel',
			                {"marker_clusters":"1","marker_cluster_imgurl":"","scrollwheel":false,"icon":"","zoom":15,"styles":null}            );
			            SABAI.GoogleMaps.autocomplete(".sabai-directory-direction-location input", {componentRestrictions: {}});
			         });
			}});
        }
        else
        {
            // Retry later...
            setTimeout(LoadGoogle, 30);
        }
    }

    LoadGoogle();

</script>
<div id="sabai-directory-map-direction-search" class="sabai-row-fluid sabai-directory-search">
    <div class="sabai-span6 sabai-directory-direction-location"><input type="text" value="" placeholder="Откуда будем выдвигаться?" /></div>
    <div class="sabai-span3 sabai-directory-direction-mode">
        <select>
            <option value="DRIVING">На машине</option>
            <option value="TRANSIT">На общественном транспорте</option>
            <option value="WALKING">Пешком</option>
            <option value="BICYCLING">На велосипеде</option>
        </select>
    </div>
    <div class="sabai-span3 sabai-directory-search-btn"><button class="sabai-btn sabai-btn-small sabai-btn-primary sabai-directory-search-submit">Построить путь</button></div>
</div>
<div id="sabai-directory-map" class="sabai-googlemaps-map" style="width:100%;height:400px;"></div>
<div id="sabai-directory-map-direction-panel" style="height:200px; overflow:scroll; display:none;"></div>