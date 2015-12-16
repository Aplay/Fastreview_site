<?php
ob_start();
$this->renderTemplate($entity->getBundleType() . '_single_infobox', array('entity' => $entity));
$content = ob_get_clean();
?>
<script type="text/javascript">
google.load("maps", "3", {other_params:"sensor=false&libraries=places&language=<?php echo $this->GoogleMaps_Language();?>", callback:function () {
    $LAB.script("<?php echo $this->JsUrl('directionmap.js', 'sabai-directory');?>")
        .script("<?php echo $this->JsUrl('autocomplete.js', 'sabai-directory');?>").wait(function(){
            SABAI.GoogleMaps.directionMap(
                "#sabai-directory-map",
                <?php echo ($lat = $entity->getSingleFieldValue('directory_location', 'lat')) ? $lat : 'null';?>,
                <?php echo ($lng = $entity->getSingleFieldValue('directory_location', 'lng')) ? $lng : 'null';?>,
                <?php echo isset($map_settings['listing_default_zoom']) ? intval($map_settings['listing_default_zoom']) : 15;?>,
                "#sabai-directory-map-directions .sabai-directory-search-btn button",
                "#sabai-directory-map-directions .sabai-directory-direction-location input",
                "#sabai-directory-map-directions .sabai-directory-direction-mode select",
                <?php echo json_encode($content);?>,
                '<?php echo $this->Directory_ListingMapMarkerUrl($entity);?>',
                <?php if ($map_settings['style']):?><?php echo json_encode($this->GoogleMaps_Style($map_settings['style']));?><?php else:?>null<?php endif;?>,
                <?php echo json_encode(array('scrollwheel' => (bool)$this->Config('Directory', 'map', 'options', 'scrollwheel')));?>
            );
            SABAI.GoogleMaps.autocomplete(".sabai-directory-direction-location input");
        });
}});
</script>
<div id="sabai-directory-map-directions" class="sabai-row-fluid sabai-directory-search">
    <div class="sabai-span6 sabai-directory-direction-location"><input type="text" value="" placeholder="<?php Sabai::_h(__('Enter a Location', 'sabai-directory'));?>" /></div>
    <div class="sabai-span3 sabai-directory-direction-mode">
        <select>
            <option value="DRIVING"><?php echo __('By car', 'sabai-directory');?></option>
            <option value="TRANSIT"><?php echo __('By public transit', 'sabai-directory');?></option>
            <option value="WALKING"><?php echo __('Walking', 'sabai-directory');?></option>
            <option value="BICYCLING"><?php echo __('Bicycling', 'sabai-directory');?></option>
        </select>
    </div>
    <div class="sabai-span3 sabai-directory-search-btn"><button class="sabai-btn sabai-btn-small sabai-btn-primary sabai-directory-search-submit"><?php echo __('Get Directions', 'sabai-directory');?></button></div>
</div>
<div id="sabai-directory-map" class="sabai-googlemaps-map" style="height:400px;"></div>