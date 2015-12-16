<?php if (empty($settings['hide_searchbox'])):?>
<?php   $this->renderTemplate('directory_searchbox', array('url_params' => $url_params, 'address' => $settings['address'], 'keywords' => isset($settings['keywords'][0]) ? implode(' ', $settings['keywords'][0]) : '', 'current_category' => $settings['category'], 'category' => $settings['parent_category'], 'category_bundle' => $settings['category_bundle']));?>
<?php endif;?>
<div id="sabai-directory-listings">
<?php if (empty($entities) && empty($is_geolocate)):?>
    <?php $this->renderTemplate('directory_listings_none', array('sorts' => $sorts, 'views' => $views, 'distances' => $distances, 'settings' => $settings));?>
<?php else:?>
    <?php $this->renderTemplate('directory_listings_' . $settings['view'], array('entities' => $entities, 'paginator' => $paginator, 'url_params' => $url_params, 'sorts' => $sorts, 'views' => $views, 'distances' => $distances, 'center' => $center, 'settings' => $settings));?>
<?php endif;?>
</div>