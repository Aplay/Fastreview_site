<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" >
    <meta content="width=device-width, initial-scale=1" name="viewport">

    <meta name="description" content="<?php echo $this->pageDescription; ?>">
    <meta name="author" content="<?php echo $this->pageAuthor; ?>">
    <meta name="keywords" content="<?php echo $this->pageKeywords; ?>">

<?php
$this->renderPartial('//layouts/__icons');
?>

    <title><?php echo $this->pageTitle; ?></title>

<?php 
$cs = Yii::app()->clientScript;
$cs->registerPackage('jquery');
$cs->registerPackage('bootstrap');

$baseUrl = Yii::app()->request->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;


Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/bower_components/animate.css/animate.min.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/vendors/socicon/socicon.min.css');
Yii::app()->clientScript->registerCssFile($themeUrl . "/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css");
Yii::app()->clientScript->registerCssFile($themeUrl . "/vendors/bower_components/mediaelement/build/mediaelementplayer.css");
Yii::app()->clientScript->registerCssFile($themeUrl . "/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css");
Yii::app()->clientScript->registerScriptFile($baseUrl.'/js/view/search_blue.js', CClientScript::POS_END);


Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.1.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.2.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/appstyle.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/fastreview.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/trunk8/trunk8.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/autosize/jquery.autosize.min.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/main.js', CClientScript::POS_END);
// Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/salvattore/salvattore.min.js', CClientScript::POS_END); 

?>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=cyrillic,latin' rel='stylesheet' type='text/css'>

   
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="mobilenav" style="display: none;">
  <div id="mobilenav_blue"></div>
      <div id="mobilenav_search" style="display: none;">
      <div class="container">
      <div class="row m-t-20">
      <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
      <form style="margin-top:116px;" class="searchform" method="get" role="search">
        <div class="form-group" style="max-width:400px;margin:0 auto;position:relative;">

        <input type="search" id="searchFieldReviewObjectBlue" name="q"  placeholder="Поиск" class="field" autocomplete="off">
        <div id="searchFieldIconBlue" style="cursor:default;" type="button"><i class="fa fa-search"></i></div>
        </div>
      </form>
      <div id="results_blue" class="m-t-30"></div>
      </div>
      </div>
      </div>
      </div>
      <div id="mobilenav_links" style="display: none;">
        <ul>
            <li data-rel="#header">
                <span class="nav-label"><a class="c-white" href="/">Главная</a></span>
            </li>
            <li data-rel="#about-us">
                <span class="nav-label">О нас</span>
            </li>
        </ul>
      </div>
</div>
<nav id="header" class="header">
<div class="container">
<ul class="navbar-nav">
<li class="pull-left">
<a class="menu-trigger" href="javascript:void(0)">
       <div class="hamburger">
         <div class="menui top-menu"></div>
         <div class="menui mid-menu"></div>
         <div class="menui bottom-menu"></div>
       </div>
    </a>
</li>
<li class="pull-left">
<a class="search-trigger" href="javascript:void(0)">
<i class="fa fa-search"></i>
</a>
</li>
<li class="pull-left">
<a class="scribe-trigger" href="/review_objects">
<i class="fa fa-pencil"></i>
</a>
</li>
<li class="pull-right">
  <ul class="header-inner">
  <li id="nav-li-last" style="position:relative;width:30px;" class="dropdown mymenu">
                    <?php if(!Yii::app()->user->isGuest){ 
                    $user_avatar = Yii::app()->user->getAvatar(true);
                    $user_link = Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username));
                      ?>
                    <a id="header_user_box" data-container="#nav-li-last"   class="user-menu" href="javascript:void(0)">
                    </a>
                    <?php if($user_avatar){ ?>
                       <img  class="header_user_box_img" src="<?php echo $user_avatar; ?>"  />
                    <?php } else { ?>
                      <span style="" class="header_user_box_span btn-label hdr icon zmdi zmdi-account"></span>
                    <?php } ?>
                   <div id="user_header_menu" class="hide">
                    <ul id="user_header_menu_ul">   
                    <li><a  href="<?php echo Yii::app()->createAbsoluteUrl('/users/user/view',array('url'=>Yii::app()->user->username));?>"><i class="md md-account-circle m-r-5"></i> Моя страница</a></li>                 
                    <li><a  href="javascript:void(0);" onclick="$('#header_user_box').popover('hide');$('#user_profile_modal').modal();return false;"><i class="md md-settings m-r-5"></i> Настройки</a></li>
                    
                    
                    <li><a  href="/logout"><i class="md md-reply flip-vertical m-r-5"></i> Выход</a></li>
                    </ul> 
                  </div>
                  <?php } else { ?>
                  <a id="header_user_box2" data-toggle="modal" data-target="#login_modal"  class="user-menu" href="javascript:void(0)"></a>
                  <span style="" class="header_user_box_span btn-label hdr icon zmdi zmdi-account"></span>
                  <!--<img  class="header_user_box_img" src="/img/site_avatar.png"  />-->
                  <?php } ?>
  </li>
  </ul>
</li>
</ul>
</div>
</nav>
<div class="container">
<?php echo $content; ?>
</div>


<?php 
$this->renderPartial('//layouts/__user');
$this->renderPartial('//layouts/__footer');
$this->renderPartial('//layouts/__counter');
?>

<input type="hidden" id="csfr" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />

</body>
</html>
