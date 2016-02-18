<aside class="col-md-3">
<div class="zn_sidebar sidebar kl-sidebar--light">
<?php
if(!empty($this->article_cats)){ ?>
<div class="widget woocommerce widget_product_categories">
<h3 class="widgettitle title">РУБРИКИ</h3> 
<ul class="product-categories">
<?php

  foreach ($this->article_cats as $k1 => $v1) { ?>
    <li class="cat-item cat-parent">
    <?php 
    $url = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$v1['url'])); 
    if(isset($v1['items']) && $v1['id'] != 0){
      
      echo CHtml::link($v1['title'], $url, array('class'=>''));
     // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v1['cnt']),' ('.$v1['cnt'].')');
      echo CHtml::openTag('ul',array('class'=>'children'));
      foreach ($v1['items'] as $k2 => $v2) {
        break;
        $url = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$v2['url']));
        echo CHtml::openTag('li',array('class'=>'cat-item'));
        if(isset($v2['items'])){
          echo CHtml::link($v2['title'], $url, array('class'=>''));
        // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v2['cnt']),' ('.$v2['cnt'].')');
          echo CHtml::openTag('ul',array('class'=>'children'));
          foreach ($v2['items'] as $k3 => $v3) {
            $url = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$v2['url'])); 
            echo CHtml::openTag('li',array('class'=>'cat-item'));
            echo CHtml::link($v3['title'], $url, array('class'=>''));
          //  echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v3['cnt']),' ('.$v3['cnt'].')');
            echo CHtml::closeTag('li');
          }
          echo CHtml::closeTag('ul');
        } else {
          echo CHtml::link($v2['title'], $url, array('class'=>''));
         // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v2['cnt']),' ('.$v2['cnt'].')');
        }
        echo CHtml::closeTag('li');
      }
      echo CHtml::closeTag('ul');
    } else if(isset($v1['items']) && $v1['id'] == 0){


      foreach ($v1['items'] as $k2 => $v2) {
        $url = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$v2['url'])); 
        echo CHtml::openTag('li',array('class'=>'cat-item'));
        if(isset($v2['items'])){
          echo CHtml::link($v2['title'], $url, array('class'=>''));
         // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v2['cnt']),' ('.$v2['cnt'].')');
          echo CHtml::openTag('ul',array('class'=>'children'));
          foreach ($v2['items'] as $k3 => $v3) {
            $url = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$v3['url'])); 
            echo CHtml::openTag('li',array('class'=>'cat-item'));
            echo CHtml::link($v3['title'], $url, array('class'=>''));
           // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v3['cnt']),' ('.$v3['cnt'].')');
            echo CHtml::closeTag('li');
          }
          echo CHtml::closeTag('ul');
        } else {
          echo CHtml::link($v2['title'], $url, array('class'=>''));
         // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v2['cnt']),' ('.$v2['cnt'].')');
        }
        echo CHtml::closeTag('li');
      }

     // echo CHtml::closeTag('ul');
    } else {

      echo CHtml::link($v1['title'], $url, array('class'=>''));
     // echo CHtml::tag('span',array('class'=>'count', 'data-cnt'=>$v1['cnt']),' ('.$v1['cnt'].')');
    } ?>
    </li>
    
  <?php } ?>
  </ul>
  </div>
<?php } 
/*
$tags = $this->article_cats;

if(!empty($tags) && isset($tags[0]['items']) && !empty($tags[0]['items'])){
?>
<div class="widget widget_categories" id="categories-2">
<h3 class="widgettitle title">ТЕГИ</h3>     
<ul class="menu">
<?php 
foreach ($tags[0]['items'] as $k=>$tag) { 

$turl = Yii::app()->createAbsoluteUrl('catalog/article/view', array('url'=>$tag['url'])); 
           
  ?>
<li class="cat-item cat-item-<?php echo $tag['id']; ?>">
  <a href="<?php echo $turl; ?>"><?php echo $tag['title']; ?></a>
</li>
<?php  if($k>=8)
    break;
} 
?>
      </ul>
    </div>
<?php 
} */
if(!empty($popular)) { ?>
<div class="widget woocommerce widget_top_rated_products" id="woocommerce_top_rated_products-2">
<h3 class="widgettitle title">ПОПУЛЯРНЫЕ СТАТЬИ</h3>
<ul class="product_list_widget">
<?php 
foreach ($popular as $pop) { 
$pop_url = Yii::app()->createAbsoluteUrl('catalog/article/item', array( 'id'=>$pop->id, 'dash'=>'-', 'itemurl'=>$pop->url));
$im = '/img/cap.gif';
$alt = '';
if($pop->logotip){ 
  $im =  $pop->getUrl('180x180','adaptiveResize',false,'logotip');

  $alt = $pop->title;
  }
?>
<li>
  <a  href="<?php echo $pop_url; ?>">
    <img height="180" width="180" alt="<?php echo $alt; ?>" class="attachment-shop_thumbnail wp-post-image" 
    src="<?php echo $im; ?>"><span class="product-title"><?php echo $pop->title; ?></span>
  </a> 
  
  </li>

<?php } ?>

</ul>
</div><!-- widget_top_rated_products -->
<?php } ?>
</div>
</aside>