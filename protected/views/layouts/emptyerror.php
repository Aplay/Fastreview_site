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

Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.1.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/app.min.2.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/appstyle.css');
Yii::app()->clientScript->registerCssFile($themeUrl . '/css/locator.css');
// Yii::app()->clientScript->registerCssFile($themeUrl . '/css/zazadun.css');

Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/jquery.debounced-resize.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/autosize/jquery.autosize.min.js', CClientScript::POS_END); 
Yii::app()->clientScript->registerScriptFile($themeUrl.'/js/plugins/salvattore/salvattore.min.js', CClientScript::POS_END); 

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
  <header id="header" class="emptypage">
            <ul class="header-inner ">
               <li data-trigger="#sidebar" id="menu-trigger" class="hidden-sm hidden-md hidden-lg">
                    <div class="line-wrap">
                        <div class="line top"></div>
                        <div class="line center"></div>
                        <div class="line bottom"></div>
                    </div>
                </li>
              
                <li class="hidden-xs" id="menu-trigger-lg" style="width:auto;padding-top:10px;margin-right:32px;">
                    <a href="/" class="main_logo theme-color">
                      <div class="main_logo_1 ArialR" style="font-size:22px; text-transform: uppercase;color:#fff;"><?php echo Yii::app()->name; ?></div>
                    </a>
                </li>

                
              
            </ul>
          


</header>
<section id="main">
<aside id="sidebar">
 <div class="sidebar-inner c-overflow">
<ul class="main-menu">
<li><a href="/"><i class="zmdi zmdi-home"></i> Локатор</a></li>
</ul>

    </div>
</aside>
<?php 

?>
<section id="content">
<div class="container" id="main_container">
<?php 
echo $content; 
?>
</div>
</section>
</section>
<?php
$this->renderPartial('//layouts/__counter');
$this->renderPartial('//layouts/__footer');

?>  
<input type="hidden" id="csfr" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />


</body>
</html>
