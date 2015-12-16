<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div class="listView" >
    <?php $this->widget('zii.widgets.CListView', array(
        'id'=>'notification_list',
        'dataProvider'=>$dataProvider,
        'itemView'=>'notification_item',
        'viewData'=>array('queueController'=>$queueController),
        'ajaxUpdate'=>false,
        'template'=>"{items}\n{pager}", 
        'tagName'=>'div',
        'pagerCssClass'=>'notification_pager',
        'emptyText'=>'',
       'pager'=>array(
            'cssFile'=>false,
            'header'=>'',
            'prevPageLabel'=>'',
            'nextPageLabel'=>Yii::t("site", "MORE NOTIFICATIONS"),
    )
      
    ));
    
   
    ?>
</div>
<?php 
if (!empty($moreUrl)){ ?>

    

<?php }

if ($dataProvider->totalItemCount > $dataProvider->pagination->pageSize) { ?>


<?php }  ?>

<script type="text/javascript">

        (function($)
        {
            
            
            // скрываем стандартный навигатор
            $('li.page').hide();
            $('li.last').hide();
            $('li.previous').hide();
            $('li.first').hide();
            // запоминаем текущую страницу и их максимальное количество
            var page = <?php echo (int)Yii::app()->request->getParam('page', 1); ?>;
            var pageCount = <?php echo (int)$dataProvider->pagination->pageCount; ?>;

            var loadingFlag = false;

            var rest = '<?php echo $count_rest; ?>';
            if(rest>0){
                $('#notif_calc').text(rest);
            } else {
                $('#notif_calc').text('').hide();    
            }

            
           
            $(".notification_pager").appendTo("#more_notification_link").addClass('notifications-link');
            
            if ( $(".yiiPager li.next").first().is(":hidden") ) {
                $(".notification_pager.notifications-link").hide();
            }
 
           $('li.next').click(function()
            {
          
                // защита от повторных нажатий
                if (!loadingFlag)
                {
                    // выставляем блокировку
                    loadingFlag = true;
                    $("#more_notification_link").show();
                    $("#main-navbar-notifications .notseen").remove();
 
                   // $('div.notification_pager').hide();
                    var datav = {'page': page + 1, 'notif_count':'<?php echo $count_rest; ?>'},
                      hpv = $('#csfr').attr('name'),
                      hpt = $('#csfr').attr('value');
                      datav[hpv] = hpt;
                    $.ajax({
                        type: 'post',
                         url: '<?php echo Yii::app()->createAbsoluteUrl("profile/notification"); ?>',
                        data: datav,
                        success: function(data)
                        {
                            if(data)
                            {
                                // увеличиваем номер текущей страницы и снимаем блокировку
                                page++;                            
                                loadingFlag = false;                            
                                $('div.notification_pager').hide();
                                $(data).hide().appendTo(".listView:last").fadeIn(1000);
                               // $(".listView:last").html(data);
                                 
                            } else {
                              // 
                               
                               
                            }
                        }
                    });
                }
                return false;
            });
            $('li.previous').click(function()
            {
          
                // защита от повторных нажатий
                if (!loadingFlag)
                {
                    // выставляем блокировку
                    loadingFlag = true;
 
                    $('div.notification_pager').hide();
       
                    $.ajax({
                        type: 'post',
                         url: '<?php echo Yii::app()->createAbsoluteUrl("profile/notification"); ?>',
                        data: {
                            // передаём номер нужной страницы методом POST
                            'page': page - 1,
                          
                        },
                        success: function(data)
                        {
                            if(data)
                            {
                                // увеличиваем номер текущей страницы и снимаем блокировку
                                page--;                            
                                loadingFlag = false;                            
                                
                             //   $(data).hide().appendTo(".listView2:last").fadeIn(1000);
                             $(".listView:last").html(data);
                                
                                 
                            } else {
                              // $('div.comments_pager').hide();
                               
                            }
                        }
                    });
                }
             
                return false;
            })
            // если достигли максимальной страницы, то прячем кнопку
            if (page >= pageCount)
            {
                $('div.comments_pager').hide();

            } 
           $('.add-tooltip').tooltip();
        })(jQuery);
        

    </script>