<?php 

$this->renderPartial('application.views.common._flashMessage');

if(!empty($dataProviderStatus->data)){
	?>
<div class="card">
<div class="card-body card-padding p-t-10">
<?php
$this->renderPartial('application.views.company.status_list',array('dataProviderStatus'=>$dataProviderStatus));

?>
</div>
</div>
<?php
} else { ?>
<div class="card">
<div class="card-body card-padding">
<div class="text-center m-t-25 p-t-15 block-header-alt">
    <h1>Ваш домашний экран</h1>
    <p class="c-gray m-t-20" style="margin-bottom:80px;">
    	Локатор уже начал размещать информацию о вашей компании. По мере поступления данных в данном разделе будут публиковаться ссылки и новости о размещении.<br>
Сразу после размещения ваших данных Локатор начнет мониторинг отзывов и упоминаний на вашу компанию.<br>
Также вам будет предоставлен доступ к мониторингу социальных сетей для поиска отзывов о вашей компании.<br>
Все уведомления и новости будут публиковаться на этой странице, а также поступят уведомления на ваш e-mail указанный при регистрации.
    </p>
</div>
</div>
</div>

<?php }

if($showtariffs){ ?>
<div class="card">
<div class="card-body card-padding">
<div class="text-center m-t-25 p-t-15 block-header-alt">
    <h1>Выберите тариф для компании <?php echo CHtml::encode($showtariffs->title); ?></h1>
    <p class="c-gray m-t-20" style="margin-bottom:80px;">
    Сейчас Локатор не работает для вас в полную силу. Как только вы выберите тариф, Локатор станет для вас активным помощником в вашем бизнесе. Продолжится размещение ваших данных на площадках и мониторинг отзывов и упоминаний.
    <br>
    <a href="<?php echo Yii::app()->createAbsoluteUrl('/payment/pay',array('id'=>$showtariffs->id)); ?>" class="btn btn-primary theme-locator m-t-30">Выбрать тариф</a>
    </p>
    
</div>
</div>
</div>
<?php }
?>
