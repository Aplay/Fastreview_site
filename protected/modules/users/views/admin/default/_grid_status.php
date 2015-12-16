<?php


if(Yii::app()->user->isSuperuser && $data->id != Yii::app()->user->id){
$roles=Rights::getAssignedRoles($data->id);
$su = 0;
foreach($roles as $role){
    if($role->name == 'Admin'){
        $su = 1;
        break;
    }

}
if($su)
    $group = Yii::t('site', 'Admin');
else 
    $group = Yii::t('site', 'User');

 ?>
<a  data-title="<?php echo Yii::t('site', 'Change status'); ?>"  data-value="<?php echo $su; ?>" data-pk="<?php echo $data->id; ?>"   id="group<?php echo $data->id; ?>" href="#" class="status editable editable-click" data-original-title="" title="" style=""><?php echo $group; ?></a>
<?php } ?>