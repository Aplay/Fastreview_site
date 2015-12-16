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


Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.1.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.2.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/appstyle.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/fastreview.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/jquery.debounced-resize.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/autosize/jquery.autosize.min.js', CClientScript::POS_END); 
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
      <div id="mobilenav_search" style="display: none;">
      <form action="/" class="searchform" method="get" role="search">
        <input type="search" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" value="Ваш текст..." name="s" class="field">
        <button id="searchsubmit" type="submit"><i class="fa fa-search"></i></button>
      </form>
      </div>
      <div id="mobilenav_links" style="display: none;">
        <ul>
            <li data-rel="#header">
                <span class="nav-label">Главная</span>
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
<li>
<a class="menu-trigger" href="javascript:void(0)">
       <div class="hamburger">
         <div class="menui top-menu"></div>
         <div class="menui mid-menu"></div>
         <div class="menui bottom-menu"></div>
       </div>
    </a>
</li>
<li>
<a class="search-trigger" href="javascript:void(0)">
<i class="fa fa-search"></i>
</a>
</li>
<li>
<a class="scribe-trigger" href="/review_objects">
<i class="fa fa-pencil"></i>
</a>
</li>
</ul>
</div>
</nav>
<div class="container">
<?php echo $content; ?>
</div>


<?php 
$this->renderPartial('//layouts/__footer');
$this->renderPartial('//layouts/__counter');
?>
<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="search-modal modal fade in" id="search-modal" style="display: none;">
    <a aria-hidden="true" data-dismiss="modal" class="close">×</a>
    <div class="modal-dialog">
      <form action="/" class="searchform" method="get" role="search">
        <input type="search" onblur="if(this.value=='')this.value=this.defaultValue;" onfocus="if(this.value==this.defaultValue)this.value='';" value="Введите ваш текст..." name="s" class="field">
        <button id="searchsubmit" type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
  </div>

<input type="hidden" id="csfr" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />

</body>
</html>
