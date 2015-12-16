<?php foreach ($this->Entity_CustomFields($entity) as $field_name => $field):?>
    <div class="sabai-directory-field sabai-field-type-<?php echo str_replace('_', '-', $field['type']);?> sabai-field-name-<?php echo str_replace('_', '-', $field_name);?> sabai-clearfix">
        <div class="sabai-field-label"><?php Sabai::_h($field['title']);?></div>
        <div class="sabai-field-value">
<?php   switch ($field['type']):?>
<?php     case 'string':?>
<?php     case 'number':?>
            <?php echo implode(', ', array_map(array('Sabai', 'h'), $field['values']));?>
<?php       break;?>

<?php     case 'choice': $choices = array();?>
<?php       foreach ($field['values'] as $value) if (isset($field['settings']['options']['options'][$value])) $choices[] = Sabai::h($field['settings']['options']['options'][$value]);?>
            <?php echo implode(', ', $choices);?>
<?php       break;?>

<?php     case 'text':?>
<?php       foreach ($field['values'] as $value):?>
            <p><?php Sabai::_h($value);?></p>
<?php       endforeach;?>
<?php       break;?>

<?php     case 'date_timestamp': $dates = array();?>
<?php       foreach ($field['values'] as $value) $dates[] = $this->DateTime($value);?>
            <?php echo implode(', ', $dates);?>
<?php       break;?>

<?php     case 'boolean':?>
<?php       if (!empty($field['values'][0])):?>
<?php         echo __('Yes', 'sabai');?>
<?php       else:?>
<?php         echo __('No', 'sabai');?>
<?php       endif;?>
<?php       break;?>

<?php     case 'markdown_text':?>
<?php       foreach ($field['values'] as $value):?>
          <?php echo $value['filtered_value'];?>
<?php       endforeach;?>
<?php       break;?>

<?php     case 'file_image':?>
<?php       $i = 0; while ($images = array_slice($field['values'], $i * 4, 4)):?>
            <div class="sabai-row-fluid">
<?php         foreach ($images as $image):?>
                <div class="sabai-span3"><?php echo $this->File_ThumbnailLink($entity, $image);?></div>
<?php         endforeach;?>
            </div>
<?php       $i++; endwhile;?>
<?php       break;?>

<?php     case 'file_file':?>
<?php       foreach ($field['values'] as $value):?>
            <div><?php echo $this->File_Link($entity, $value);?></div>
<?php       endforeach;?>
<?php       break;?>

<?php     case 'user':?>
<?php       foreach ($field['values'] as $value):?>
            <?php echo $this->UserIdentityLinkWithThumbnailSmall($value);?>&nbsp;
<?php       endforeach;?>
<?php       break;?>
<?php   endswitch;?>
        </div>
    </div>
<?php endforeach;?>
