<div class="row">
<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 text-center">
<h3 class="c-blue" style="margin-top:80px;">FAST REVIEW</h3>
<p class="m-t-5">СЕРВИС БЫСТРЫХ ОТЗЫВОВ. МЫ ЗНАЕМ ВСЕ И ОБО ВСЕМ - <br>
ПРЕИМУЩЕСТВА И НЕДОСТАТКИ!</p>
<form role="form" style="margin-top:60px;" id="mainSearchForm">
<div class="form-group" style="max-width:400px;margin:0 auto;position:relative;">
    <input  type="search" placeholder="ВОСПОЛЬЗУЙТЕСЬ ПОИСКОМ" id="searchField" class="form-control input-sm">
<div class="searchFieldIcon" onclick="$('#mainSearchForm').submit();"><i class="fa fa-search"></i></div>
</div>
</form>
</div>
</div>
<div class="row" style="margin-top:60px;">
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
<div class="row-list-3">
<?php 
if(!empty($cats)){
    foreach ($cats as $cat) {
        $cat_url = Yii::app()->createAbsoluteUrl('/fastreview/view', array('url'=>$cat->url));
        echo '<div class="key t-uppercase"><a class="nocolor" href="'.$cat_url.'">'.$cat->title.'</a></div>';
    }
}
?>
</div>
</div>
</div>
<div class="row" style="margin-top:60px;">
<div class="col-xs-12 text-center">
ПОСЛЕДНИЕ ОБНОВЛЕНИЯ
</div>
</div>
<div class="row m-t-25">
<div class="col-xs-12 col-lg-10 col-lg-offset-1">
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="media">
		<div class="pull-left">
            <img alt="" src="/img/avatar.png" class="lv-img-lg" />
        </div>
        <div class="media-body m-t-5">
        <p class="m-b-5">SANTA MONICA, CALIFORNIA</p>
        <div class="pull-left">
        <!--<img alt="" src="/img/gud.png" />-->
        <button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
        </div>
        <div class="pull-left p-l-10 c-6">
        	<div class="f-11">Местоположение</div>
        	<div class="f-10">Считают 56%</div>
        </div>
        </div>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="media">
		<div class="pull-left">
            <img alt="" src="/img/avatar.png" class="lv-img-lg" />
        </div>
        <div class="media-body m-t-5">
        <p class="m-b-5">SANTA MONICA, CALIFORNIA</p>
        <div class="pull-left">
        <button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
        </div>
        <div class="pull-left p-l-10 c-6">
        	<div class="f-11">Местоположение</div>
        	<div class="f-10">Считают 56%</div>
        </div>
        </div>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="media">
		<div class="pull-left">
            <img alt="" src="/img/avatar.png" class="lv-img-lg" />
        </div>
        <div class="media-body m-t-5">
        <p class="m-b-5">SANTA MONICA, CALIFORNIA</p>
        <div class="pull-left">
        <button type="button" class="btn btn-success btn-icon btn-icon waves-effect waves-circle waves-float finger-circle"><i class="fa fa-thumbs-up"></i></button>
        </div>
        <div class="pull-left p-l-10 c-6">
        	<div class="f-11">Местоположение</div>
        	<div class="f-10">Считают 56%</div>
        </div>
        </div>
	</div>
</div>
</div>
</div>
</div>
<div class="row" style="margin-top:60px;">
<div class="col-xs-12  visible-xs text-center">
<img style="display:inline-block;" class="img-responsive" src="/img/colophon.png" />
</div>
<div class="hidden-xs col-sm-6  text-right">
<img style="display:inline-block;" class="img-responsive" src="/img/colophon.png" />
</div>
<div class="col-xs-12 col-sm-6  text-left">
<p class="m-t-30">ПЕРЕД ПОКУПКОЙ ТОВАРА ВЫ СМОЖЕТЕ УЗНАТЬ <br>
ЕГО ПРЕИМУЩЕСТВА И НЕДОСТАТКИ <br>
ИСПОЛЬЗУЙТЕ ПОИСК ПО: </p>
<p><strong>ФОТО ЭТИКЕТКИ</strong></p>
<p><strong>ШТРИХ КОДУ</strong></p>
<p><strong>НАЗВАНИЮ</strong></p>
<img src="/img/appstore.png" />
<img src="/img/googleplay.png" />
</div>
</div>
