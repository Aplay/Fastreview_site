
<div class="follower">
      <a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>$data->username)); ?>"><img src="<?php echo $data->getAvatar(); ?>" alt="" class="follower-avatar"></a>
      <div class="body">
        <div class="follower-controls">
        <?php
        $status_friend = $data->isFriendOf(Yii::app()->user->id);
 
        if($status_friend == UserFriend::CONTACT_REQUEST) {// дружба еще не подтверждена
             echo Yii::t('site','New contact').'&nbsp; ';
             if($data->heInviteMe(Yii::app()->user->id)){ // он меня пригласил дружить
               
              ?>
              <a class="btn btn-xs btn-outline btn-success add-tooltip" title="" href="<?php echo Yii::app()->createUrl('profile/acceptContact', array('id'=>$data->id)); ?>" data-original-title="Contact"><i class="fa fa-check"></i></a>
              <a class="btn btn-xs btn-outline btn-danger add-tooltip" title="" href="<?php echo Yii::app()->createUrl('profile/rejectContact', array('id'=>$data->id)); ?>" data-original-title="<?php echo Yii::t('site','Cancel contact'); ?>"><i class="fa fa-times"></i></a>
              <?php
             // echo CHtml::link(Yii::t('site', 'Contact'),Yii::app()->createUrl('profile/acceptContact', array('id'=>$data->id)), array('class'=>'btn btn-sm btn-default', 'title'=>Yii::t('site', 'Add contact')));  
             } else { // я его пригласил
              ?>
              <a class="btn btn-xs btn-outline btn-danger add-tooltip" title="" href="<?php echo Yii::app()->createUrl('profile/rejectContact', array('id'=>$data->id)); ?>" data-original-title="<?php echo Yii::t('site','Cancel contact'); ?>"><i class="fa fa-times"></i></a>
             <?php
              //  echo CHtml::link(Yii::t('site', 'Cancel contact'),Yii::app()->createUrl('profile/rejectContact', array('id'=>$data->id)), array('class'=>'btn btn-sm btn-default', 'title'=>Yii::t('site', 'Cancel contact')));
             }
        } elseif($status_friend == UserFriend::CONTACT_ACCEPTED){ // дружба подтверждена

         //  echo CHtml::link('<i class="fa fa-check"></i>&nbsp;&nbsp;'.Yii::t('site', 'Contact'),Yii::app()->createUrl('profile/removeContact', array('id'=>$data->id)), array('class'=>'btn btn-sm btn-success','title'=>Yii::t('site', 'Remove contact'), 'confirm' => Yii::t('site','Do you really want to remove contact?'))); 
           ?>
            <div class="btn-group">
              <button data-toggle="dropdown"  class="btn btn-sm btn-success dropdown-toggle" type="button"><i class="fa fa-check"></i>&nbsp;&nbsp;<?php echo Yii::t('site', 'Contact'); ?></button>
              <ul class="dropdown-menu pull-right">
                <li>
                <?php
                echo CHtml::link(Yii::t('site', 'Remove contact'),Yii::app()->createUrl('profile/removeContact', array('id'=>$data->id)), array('class'=>'ui-bootbox-confirm','title'=>Yii::t('site', 'Remove contact'),'data-confirmtext' => Yii::t('site','Do you really want to remove contact?'))); 
                ?>
                </li>
              </ul>
            </div>
            <?php 
                 
        } else { 

          echo CHtml::link(Yii::t('site', 'Contact'), Yii::app()->createUrl('profile/addContact', array('id'=>$data->id)), array('title'=>Yii::t('site', 'Contact'), 'class'=>'btn btn-sm btn-info'));
                 
        }
        ?>
        </div>
        <a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>$data->username)); ?>" class="follower-name"><?php echo $data->fullname; ?></a><br>
        <a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>$data->username)); ?>" class="follower-username"><?php echo $data->username; ?></a>
      </div>
</div>

