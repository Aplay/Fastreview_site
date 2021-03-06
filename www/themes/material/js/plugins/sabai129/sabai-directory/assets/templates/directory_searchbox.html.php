<div class="sabai-row-fluid sabai-directory-search">
    <form method="get" action="<?php echo isset($action_url) ? $action_url : $this->Url($CURRENT_ROUTE);?>">
<?php if ($category_select = $this->Taxonomy_SelectDropdown($category_bundle, array('name' => 'category', 'class' => 'sabai-pull-right', 'parent' => $category, 'current' => $current_category, 'default_text' => __('Select category', 'sabai-directory')))):?>
        <div class="sabai-span4 sabai-directory-search-keyword"><input name="keywords" type="text" value="<?php Sabai::_h($keywords);?>" placeholder="<?php Sabai::_h(__('Search for', 'sabai-directory'));?>" /></div>
        <div class="sabai-span4 sabai-directory-search-location"><input name="address" type="text" value="<?php Sabai::_h($address);?>" placeholder="<?php echo __('Enter a location', 'sabai-directory');?>" /></div>
        <div class="sabai-span3 sabai-directory-search-category"><?php echo $category_select;?></div>
        <div class="sabai-span1 sabai-directory-search-btn"><button class="sabai-btn sabai-btn-small sabai-btn-primary sabai-directory-search-submit"><i class="sabai-icon-search"></i></button></div>
<?php else:?>
        <div class="sabai-span5 sabai-directory-search-keyword"><input name="keywords" type="text" value="<?php Sabai::_h($keywords);?>" placeholder="<?php Sabai::_h(__('Search for', 'sabai-directory'));?>" /></div>
        <div class="sabai-span5 sabai-directory-search-location"><input name="address" type="text" value="<?php Sabai::_h($address);?>" placeholder="<?php echo __('Enter a location', 'sabai-directory');?>" /></div>
        <div class="sabai-span2 sabai-directory-search-btn"><button class="sabai-btn sabai-btn-small sabai-btn-primary sabai-directory-search-submit"><i class="sabai-icon-search"></i></button></div>
<?php endif;?>
    </form>
</div>
<?php if (!isset($action_url)):?> 
<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery(".sabai-directory-search-submit").click(function(e){
        var $this = jQuery(this),
            form = $this.closest("form"),
            formData = form.find(":input[value][value!='<?php Sabai::_h(__('Search for', 'sabai-directory'));?>']").serialize();
        SABAI.ajax({
            trigger: $this,
            type: form.attr("method"),
            target: "#sabai-directory-listings",
            scrollTo: "#sabai-directory-listings",
            url: form.attr("action"),
            data: "<?php echo http_build_query($url_params);?>&" + formData + "&<?php echo Sabai_Request::PARAM_AJAX;?>=" + encodeURIComponent("#sabai-directory-listings")
        });
        e.preventDefault();
    }); 
});
</script>
<?php endif;?>