<div id="<?php echo $id;?>" class="<?php echo $class;?> sabai-clearfix sabai-row-fluid">
    <div class="sabai-directory-photobox sabai-span7">
        <div class="sabai-directory-photo">
            <a href="<?php echo $this->Directory_PhotoUrl($entity, 'large');?>" rel="prettyPhoto" title="<?php Sabai::_h($entity->getTitle());?>">
                <img src="<?php echo $this->Directory_PhotoUrl($entity, 'large');?>" alt="" />
            </a>
        </div>
        <div class="sabai-directory-photo-title">
            <strong><?php echo $this->Content_RenderTitle($entity, false, null, null, '', $entity->getTitle());?></strong>
            <span><?php echo $this->Directory_RenderPhotoMeta($entity, !empty($link_to_listing));?></span>
        </div>
        <div class="sabai-directory-photo-stats">
<?php if (!empty($entity->voting_helpful[0]['sum'])):?>
            <span><i class="sabai-icon-thumbs-up"></i> <?php echo $entity->voting_helpful[0]['sum'];?></span>
<?php endif;?>
<?php if (!empty($entity->data['comment_count'])):?>
            <span><i class="sabai-icon-comment"></i> <?php echo $entity->data['comment_count'];?></span>
<?php endif;?>
        </div>
        <div class="sabai-directory-photo-actions sabai-btn-group">
            <?php echo $this->ButtonLinks($links);?>
        </div>
    </div>
    <div class="sabai-directory-comments sabai-span5" id="<?php echo $id;?>-comments">
        <?php echo $this->Comment_RenderComments($entity, $id . '-comments', true);?>
    </div>
</div>