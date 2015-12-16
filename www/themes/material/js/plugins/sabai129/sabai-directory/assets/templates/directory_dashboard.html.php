<div class="sabai-btn-toolbar sabai-clearfix">
    <div class="sabai-btn-group sabai-pull-right"><?php echo implode(PHP_EOL, $links);?></div>
</div>
<?php echo $this->Form_Render($form, $form_js);?>
<?php if ($paginator->count()):?>
<div class="sabai-pagination sabai-pagination-centered sabai-directory-pagination sabai-clearfix">
  <?php echo $this->PageNav($CURRENT_CONTAINER, $paginator, $this->Url($CURRENT_ROUTE));?>
</div>
<?php endif;?>
