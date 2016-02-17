<?php $active = $this->getActiveFilters($this->rootcat); ?>
<?php  if($this->rootcat=='services') { ?>
<style>
    nav.menu .filters ul li.categories em {
      left:0px;
     }
     nav.menu .filters ul li.categories em:after {
      left:14px;

       }
</style>
<?php } ?>
<div id="filter_box"> 
         <div id="filter_panel" >
           
            <div class="actions" id="search_summary-actions">
                

         
        
            <div  style="float:left; margin-top:17px; margin-bottom: 14px">
            
               <nav class="menu">
               <div class="filters">
                <ul>
                    <?php  if($this->rootcat=='services'){ ?>
                    
                        <li class="categories"><span class="bt fsearch">Категории</span><em>Фильтр&nbsp;категорий</em></li>
                   
                    
                   <?php } else { ?>
                        <li class="categories"><span class="bt fsearch">Категории</span><em>Фильтр&nbsp;категорий</em></li>
                    <li class="brands"><span class="bt fsearch">Бренды</span><em>Фильтр&nbsp;брендов</em></li>
                    <li class="preference"><span class="bt fsearch">Предпочтения</span><em>Фильтр&nbsp;предпочтений</em></li>
                    <li class="colors"><span class="bt fsearch">Цвета</span><em>Фильтр&nbsp;цвета</em></li>
                    <li class="shops"><span class="bt fsearch">Магазины</span><em>Фильтр&nbsp;магазинов</em></li>
                  <?php  } ?>
                    
                </ul>
            </div>
            </nav>
         </div>
                      
           
            
        <?php echo CHtml::link('> Создать объявление', Yii::app()->createAbsoluteUrl('/publication'), array('id'=>'add_ob_but')); ?>
        <div id="seller_top_basket">
           <?php $this->renderFile(Yii::getPathOfAlias('orders.views.cart._small_cart').'.php'); ?>
            </div>
            <div id="seller_basket_pul"></div>


        <?php
        if ($this->rootcat=='services'){
            $stylee = ' style="margin-left:47px;  margin-right:480px"';
        } else {
            $stylee = ' style="margin-left:165px; margin-right:480px"';
        }
         ?>
         <div <?php echo $stylee; ?> > 
               
       <nav class="menu">
     
        <div id="seller_filter_panel" class="searchitems">
            <?php
         /*   if(!empty($active))
            {
               
               foreach ($active as $act){
                 
                    echo CHtml::link('<span>'.$act['label'].'</span>', $act['url']);         
                } 
            }*/
            ?>
            <?php

            if(!empty($active))
            {
               
               $mode_array = array(); 
               foreach ($active as $act){
                    if(isset($act['submode']) and $act['submode'] == 'color')
                        $mode = 'attributes_color';
                    else 
                        $mode = $act['mode'];
                    
                    $mode_array[$mode][] = '<span>'.$act['label'].'</span>'.CHtml::link('<span title="Убрать фильтр" class="iks_filter"> (x)</span>', $act['url']);
                 //   echo CHtml::link('<span>'.$act['label'].'</span>', $act['url']);         
                } 
                ?>

                <!--<div class="separator_breadcrumbs">//</div>-->
                <?php
                echo CHtml::link('Очистить все <:> ', $this->getOwner()->createUrl('view', array('url'=>$this->rootcat)), array('id'=>'clear_all_filters', 'class'=>'c9 i', 'title'=>'Очистить все'));


            } else {
              echo '<div id="filtr_title_do" class="active">_ Выбор фильтров</div>';
            }
            
            $flag_select = false;
            ?>
            
            <?php
            if(!empty($mode_array['category'])){
                echo '<div style="float:left;">Выбор по категориям:</div>';
                foreach($mode_array['category'] as $put){
                    echo $put;
                }
                $flag_select = true;
            }
            if(!empty($mode_array['brand'])){
                
                if($flag_select === true){
                    echo '<div style="float:left;margin-left:7px;"> // ';
                } else {
                    echo '<div style="float:left;">';
                }
                echo 'Выбор по брендам:</div>';
                foreach($mode_array['brand'] as $put){
                    echo $put;
                }
                $flag_select = true;
            }
            if(!empty($mode_array['attributes'])){
                
                if($flag_select === true){
                    echo '<div style="float:left;margin-left:7px;"> // ';
                } else {
                    echo '<div style="float:left;">';
                }
                echo 'Выбор по предпочтениям:</div>';
                foreach($mode_array['attributes'] as $put){
                    echo $put;
                }
                $flag_select = true;
            }
            if(!empty($mode_array['attributes_color'])){
                
                if($flag_select === true){
                    echo '<div style="float:left;margin-left:7px;"> // ';
                } else {
                    echo '<div style="float:left;">';
                }
                echo 'Выбор по цвету:</div>';
                foreach($mode_array['attributes_color'] as $put){
                    echo $put;
                }
                $flag_select = true;
            }
            if(!empty($mode_array['shop'])){
                
                if($flag_select === true){
                    echo '<div style="float:left;margin-left:7px;"> // ';
                } else {
                    echo '<div style="float:left;">';
                }
                echo 'Выбор по магазинам:</div>';
                foreach($mode_array['shop'] as $put){
                    echo $put;
                }
                $flag_select = true;
            }
            if(!empty($mode_array['account'])){
                
                if($flag_select === true){
                    echo '<div style="float:left;margin-left:7px;"> // ';
                } else {
                    echo '<div style="float:left;">';
                }
                echo 'Выбор по продавцам:</div>';
                foreach($mode_array['account'] as $put){
                    echo $put;
                }
            }
            ?>
            
            <div style="clear:both"></div>
        </div>
        </nav>       
        </div>         
       
       <div style="clear:both"></div>   
      </div> <!-- end #search_summary-actions -->
       
         <div style="clear:both"></div>     
        </div> <!-- end #filter_panel -->
   <div id="subfilters_block" style="padding:0 52px 0 28px">
     <?php

if(!empty($categories['filters']))
{

   
    foreach($categories['filters'] as $filter)
    {
 

       // Filter link was selected.
      /*  if(in_array($filter['queryParam'], $activecat)) 
        {
            if($filter['count'] > 0) {

            } else {

            }
        } else {*/
         //   if($filter['count'] > 0) {
                // main useful place, make add subcategory menu
                ?>
                
                
                <?php 
                if(isset($filter['descendants']['items']))
                {

                ?>
                <div class="seller_wrapper" style="position:relative;width:100%;"><div style="position:relative;width:100%;">
                <div data="<?php echo $filter['url_name']; ?>" id="subcat_menu_<?php echo $filter['url_name']; ?>" class="subcat_menu">
                <?php
                $this->widget('zii.widgets.CMenu',array('items'=>$filter['descendants']['items'],'htmlOptions' => array(
                        'class'=>'simplenavcat',
                            ),));
                 ?></div></div></div>
                 <?php
                 }
                 ?>
                 
                <?php
          //  } 
     //  }
    }
}
?>   
        </div>             
   <div class="box_search">
    <div id="filter_categories" class="filter categories">
        <?php 
        
        Yii::import('application.modules.store.models.Categories');
        $items = Categories::model()->findByPk($this->setcat)->asCMenuArray();

         $cnt = 0;
        if(!empty($categories['filters']))
  {


    echo CHtml::openTag('ul');
        $activecat = array();
        if(!empty($active)){

            foreach($active as $act){
              if($act['mode'] == 'category'){
                    $activecat[] = $act['titlecat'];
              }
            }      
        } 
                 
    foreach($categories['filters'] as $filter)
    {

      // url_name = avtokresla
      $url = Yii::app()->request->addUrlParamCat(array($filter['url_name']), 'page');
      $queryData = explode(';', Yii::app()->request->getQuery('category'));



            $cnt++; 
                        
      // Filter link was selected.
      if(in_array($filter['queryParam'], $activecat)) {
                $cnt++; 
                if($filter['count'] > 0) {
                echo '<li data="'.$filter['url_name'].'" class="hoveringstrong active">('.$filter['count'].') '.$filter['title'].'<input type="hidden" name="search[categories]['.$filter['url_name'].']" value="'.$filter['url_name'].'" /></li>';
        } else {
                echo '<li data="'.$filter['url_name'].'">('.$filter['count'].') '.$filter['title'].' </li>';
        }
      } else {
        $cnt++; 
                if($filter['count'] > 0) {
                echo CHtml::link('<li data="'.$filter['url_name'].'"><em>('.$filter['count'].') <span>'.$filter['title'].'<input type="hidden" name="search[categories]['.$filter['url_name'].']" value="'.$filter['url_name'].'" /></span></em></li>',$url);
        } else {
                echo '<li data="'.$filter['url_name'].'">('.$filter['count'].') '.$filter['title'].' </li>';
        }
        }
                        

    }
                
    echo CHtml::closeTag('ul');
  } 
        if($cnt == 0){
            echo CHtml::openTag('ul');
            echo '<li style="background:none"></li>Объявлений  не предложено.';
            echo CHtml::closeTag('ul');
        }
        ?>

    </div>
      <div id="filter_brands" class="filter brands">
        <?php  
            $cnt = 0;
           if(!empty($manufacturers['filters']))
  {


    echo CHtml::openTag('ul');
                
                
    foreach($manufacturers['filters'] as $filter)
    {
      // queryKey = brand
      $url = Yii::app()->request->addUrlParam('/store/catalog/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany'], 'page');
      $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));

             
                        
      // Filter link was selected.
      if(in_array($filter['queryParam'], $queryData))
      {
        // Create link to clear current filter
                                 $cnt++;
        //$url = Yii::app()->request->removeUrlParam('/store/catalog/view', $filter['queryKey'], $filter['queryParam']);
        echo '<li class="active"><em>('.$filter['count'].') '.$filter['title'].'<input type="hidden" name="search[brands]['.$filter['url_name'].']" value="'.$filter['url_name'].'" /></em></li>';
      } elseif($filter['count'] > 0) {
                                $cnt++;
        echo CHtml::link('<li><em>('.$filter['count'].') <span>'.$filter['title'].'<input type="hidden" name="search[brands]['.$filter['url_name'].']" value="'.$filter['url_name'].'" /></span></em></li>', $url);
                        } else {
        echo '';
                            // echo '<li><em><input type="hidden" name="search[brands]['.$filter['url_name'].']" value="'.$filter['url_name'].'" />'.$filter['title'].'</em> <span>(0)</span></li>';
                        }

    }
                
    echo CHtml::closeTag('ul');
  } 
        if($cnt == 0){
            echo CHtml::openTag('ul');
            echo '<li style="background:none"></li>В этой категории объявлений с известными марками не предложено.';
            echo CHtml::closeTag('ul');
        }
        ?>
        
        
           
       </div>
       <div id="filter_preference" class="filter preference">
           
               <?php
               $cnt = 0;
               foreach($attributes as $attrData)
  {
    if($attrData['title'] != 'Цвет'){
                    
                

                
    echo CHtml::openTag('ul'); 
    foreach($attrData['filters'] as $filter)
    {
      
      // queryKey = condition
      $url = Yii::app()->request->addUrlParam('/store/catalog/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany'], 'page');
      $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));


      // Filter link was selected.
      if(in_array($filter['queryParam'], $queryData))
      {
        // Create link to clear current filter
                                $cnt++;
        //$url = Yii::app()->request->removeUrlParam('/store/catalog/view', $filter['queryKey'], $filter['queryParam']);
        echo '<li class="active"><em>('.$filter['count'].') '.$filter['title'].'<input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></em></li>';
      } elseif($filter['count'] > 0) {
                                $cnt++;
        echo CHtml::link('<li><em>('.$filter['count'].') <span>'.$filter['title'].'<input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></span></em></li>', $url);
                        } else {
                            echo '';
                            //echo '<li><em><input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" />'.$filter['title'].'</em> <span>(0)</span></li>';
                        }

    }
    echo CHtml::closeTag('ul');
            }
  }
        if($cnt == 0){
            echo CHtml::openTag('ul');
            echo '<li style="background:none"></li>В этой категории объявлений с предпочтениями не предложено.';
            echo CHtml::closeTag('ul');
        }
        ?>
           
           </div>

    <div id="filter_colors" class="filter colors" style="height:100px;">
        <?php
            $cnt = 0;
               foreach($attributes as $attrData)
  {
    if($attrData['title'] == 'Цвет'){
                    
                

                
    echo CHtml::openTag('ul'); 
    foreach($attrData['filters'] as $filter)
    {
      // queryKey = color
      $url = Yii::app()->request->addUrlParam('/store/catalog/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany'], 'page');
      $queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));


      // Filter link was selected.
      if(in_array($filter['queryParam'], $queryData))
      {
        // Create link to clear current filter
                                $cnt++;
        //$url = Yii::app()->request->removeUrlParam('/store/catalog/view', $filter['queryKey'], $filter['queryParam']);
        echo CHtml::link('<li class="active '.$filter['classname'].'" style="color:#f11c41"><em><span>'.$filter['title'].'<input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></span></em>  ('.$filter['count'].')</li>', '#', array('style'=>'font-style:italic;','class'=>'active'));
      } elseif($filter['count'] > 0) {
                                $cnt++;
        echo CHtml::link('<li  class="'.$filter['classname'].'"><em><span>'.$filter['title'].'<input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></span></em>  ('.$filter['count'].')</li>', $url, array('style'=>'font-style:italic;'));
                        } else {
                            
                            echo '<a class="active" href="#"><li class="empty"><em><span>'.$filter['title'].'<input type="hidden" name="search['.$filter['queryKey'].']['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></span></em></li></a>';
                        }

    }
    echo CHtml::closeTag('ul');
            }
  }
        if($cnt == 0){
            echo CHtml::openTag('div',array('style'=>'margin-top:-10px'));
            echo 'В этой категории объявлений с цветами не предложено.';
            echo CHtml::closeTag('div');
        }
        ?>
    </div>

    <div id="filter_shops" class="filter shops">
        <?php

         $cnt = 0;
         if(!empty($shops['filters'])) {

        		echo CHtml::openTag('ul');
        		foreach($shops['filters'] as $filter) 	{

        			$url = Yii::app()->request->addUrlParam('/store/catalog/view', array($filter['queryKey'] => $filter['queryParam']), $shops['selectMany'], 'page');
        			$queryData = explode(';', Yii::app()->request->getQuery($filter['queryKey']));
        			// Filter link was selected.
        			if(in_array($filter['queryParam'], $queryData)) {

        				// Create link to clear current filter
                 $cnt++;
        				echo '<li class="active"><em>('.$filter['count'].') '.$filter['title'].'<input type="hidden" name="search[shops]['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></em></li>';
        			} elseif($filter['count'] > 0) {
                $cnt++;
        				echo CHtml::link('<li><em>('.$filter['count'].') '.$filter['title'].'<input type="hidden" name="search[shops]['.$filter['queryParam'].']" value="'.$filter['queryParam'].'" /></em></li>', $url);
              } else {
        				echo '';
                                    // echo '<li><em><input type="hidden" name="search[shops]['.$filter['url_name'].']" value="'.$filter['url_name'].'" />'.$filter['title'].'</em> <span>(0)</span></li>';
              }

        		}
                        
        		echo CHtml::closeTag('ul');
                        
        	} 
        if($cnt == 0){
            echo CHtml::openTag('ul');
            echo '<li style="background:none"></li>В вашем городе магазинов пока не открыто. <a id="know_more_shops" class="f11 redis i" href="/useful/help/view/usloviya-otkritiya-magazina">Знать больше.</a>';
            echo CHtml::closeTag('ul');
        }

        ?>
    </div>
    <div style="clear:both"></div>
    <div class="actions">
        <span class="button close"></span>

    </div>
       <div style="clear:both"></div>
       <div id="brd_gal"></div>
</div>  <!-- end box search -->  
    
        </div>
<div id="filter_another_box">
  <div id="seller_changer" style="float:left; padding-top: 7px;  width:187px; margin-bottom: 7px;"> 
           
              <div style="float:left; margin-top:2px;width:146px;" class="tooltipfil">
                  
                  <span style="background-color: #605C59; color:#fff; height:18px; padding:5px 9px 0 9px; line-height: 12px; ">
                      
              <?php $a = 0;
                        if(Yii::app()->request->getQuery('sort')){
                            if(Yii::app()->request->getQuery('sort') == 'created.desc'){
                                echo '> Последние'; $a = 1;
                            } elseif(Yii::app()->request->getQuery('sort') == 'created'){
                                echo '> Первые'; $a = 2;
                            } elseif(Yii::app()->request->getQuery('sort') == 'price'){
                                echo '> Дешевые'; $a = 3;
                            } elseif(Yii::app()->request->getQuery('sort') == 'price.desc'){
                                echo '> Дорогие'; $a = 4;
                            } elseif(Yii::app()->request->getQuery('sort') == 'views_count.desc'){
                                echo '> Популярные'; $a = 5;
                            }
                            
                        } else {
                            echo '> Последние'; $a = 1;
                        }
                        ?></span>
                  
                  <div class="tooltip-content sort_new">
                    <i></i>
                      <div class="sort_mode">
                        <div id="select_sort_mode" class="select">
                             
                        <ul>
                          <?php if($a!=1) { ?><li><a href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view', array('sort'=>'created.desc'), false, 'page'); ?>">Последние</a></li><?php } ?>
                          <?php if($a!=2) { ?><li><a href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view', array('sort'=>'created'), false, 'page'); ?>">Первые</a></li><?php } ?>
                          <?php if($a!=4) { ?><li><a href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view', array('sort'=>'price.desc'), false, 'page'); ?>">Дорогие</a></li><?php } ?>
                          <?php if($a!=3) { ?><li><a href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view', array('sort'=>'price'), false, 'page'); ?>">Дешевые</a></li><?php } ?>
                          <?php if($a!=5) { ?><li><a href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view', array('sort'=>'views_count.desc'), false, 'page'); ?>">Популярные</a></li><?php } ?>
                        </ul>
                            

                        </div>
                        <div style="margin:7px 0px 5px 0px; cursor: default;font-style: italic; border-top: 1px solid #fff; padding: 5px ">ПОРЯДОК ОБЪЯВЛЕНИЙ</div>
                       </div><!-- end sort_mode -->
                      
                  </div>
              </div>
             <?php /* ?>
            <div class="tooltipfil items_layout" style="margin-top:2px;"><span>
                      <ul>
                          <?php 
                      
                          if($itemView==='_product_wide') { ?>
                          <li style="width:28px;"><a href="#" class="items_layout_table <?php if($itemView==='_product_wide') echo 'active'; ?>" title="Список">Список</a></li>
                          <?php } else { ?>
                          <li style="width:28px;"><a href="#" class="items_layout_grid <?php if($itemView==='_product') echo 'active'; ?>" title="Галерея">Галерея</a></li>    
                          <?php } ?>
                        
                        
                      </ul></span>
                    <div class="tooltip-content sort_structure">
                        <i></i>
                       <div class="items_layout white" style="width:84px; padding:0px 0px 12px 108px; margin: 12px 0 6px 0; border-bottom:1px solid #fff;float:left;clear:both">
                      <ul>
                        <li><a  href="<?php echo Yii::app()->request->addUrlParam('/store/catalog/view',  array('view'=>'wide')) ?>" class="items_layout_table <?php if($itemView==='_product_wide') echo 'active'; ?>" title="Список">Список</a></li>
                        <li><a  href="<?php 
                      echo  Yii::app()->request->addUrlParam('/store/catalog/view',  array('view'=>'norm'));
                        ?>" class="items_layout_grid <?php if($itemView==='_product') echo 'active'; ?>" title="Галерея">Галерея</a></li>
                      </ul>
                    
                    </div> 
                        <div style="margin:0px 0px 26px 35px; line-height: 14px; cursor: default;font-style: italic">ТИП ОТОБРАЖЕНИЯ</div>
                    </div>
                    </div>
             <?php */ ?>
              <div id="search_page_size-panel2" class="tooltipfil" style="margin-left:10px"><span>
                        <?php
        $limits=array(Yii::app()->request->removeUrlParam('/store/catalog/view', 'per_page')  => $this->allowedPageLimit[0]);
        array_shift($this->allowedPageLimit);
        foreach($this->allowedPageLimit as $l)
          $limits[Yii::app()->request->addUrlParam('/store/catalog/view', array('per_page'=> $l), false, 'page')]=$l;


                                if($limits){
                                    echo '<ul id="pageul2">';
                                    $cnt = 0;
                                    foreach($limits as $lim){
                                        $cnt++;
                                         if($per_page == $lim){
                                      //  if((!Yii::app()->request->getQuery('per_page') && $cnt == 1) || Yii::app()->request->getQuery('per_page') == $lim){
                                        echo '<li class="active">'.$lim.'</li>';
                                        }
                                    }
                                    echo '</ul>';
                                }
      ?></span>
                  <div class="tooltip-content sort_page" >
                      <i></i>
                     <div id="search_page_size-panel" style="text-align:right; float:right; width:128px; padding:0px 5px 11px 59px; margin:10px 0 6px 0; border-bottom:1px solid #fff;">
                        <?php
        
                                if($limits){
                                    echo '<ul id="pageul">';
                                    $cnt = 0;
                                    foreach($limits as $lim){
                                        $cnt++;
                                       
                                        $link = Yii::app()->request->addUrlParam('/store/catalog/view', array('per_page'=>$lim), false, 'page'); // add all exclude page

                                        echo '<a href="'.$link.'"><li ';
                                        if($per_page == $lim){
                                       // if((!Yii::app()->request->getQuery('per_page') && $cnt == 1) || Yii::app()->request->getQuery('per_page') == $lim){
                                        echo ' class="activeb"';    
                                        } 
                                        echo '>'.$lim.'</li></a>';
                                    }
                                    echo '</ul>';
                                }
      ?>
                    </div> 
                      <div style="text-align:right; margin:0px 10px 26px 0px; line-height: 14px; cursor: default;font-style: italic">ОБЪЯВЛЕНИЙ НА<br> СТРАНИЦЕ</div>
            
          </div> 
         </div>  
                   
        </div>  
        <div id="summary_count_items">
        </div>
        <div id="seller_repeat_pager"></div>
        <div style="clear: both"></div>
</div>

