<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

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
	$baseUrl = Yii::app()->request->baseUrl;
	$themeUrl = '/themes/bootstrap_311/';
	$assetsPackage=array(
                    'baseUrl'=>$themeUrl,
                    'js'=>array(
                        'js/bootstrap.min.js',
                        'js/pixel-admin.min.js',
                      //  'js/demo.js'
                    ),
                    'css'=>array(
						'css/bootstrap.min.css',
						'css/pixel-admin.css',
						'css/pages.css',
                        'css/rtl.css',
                        'css/themes.css',
                        'css/stylelogin.css'
                    ),
                    'depends'=>array('jquery'),
 
                );

	$cs->addPackage('bootstrap', $assetsPackage);
    $cs->registerPackage('bootstrap');

    
    ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/ie.min.js"></script>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
  
</head>


        <!-- Right side -->
       <?php echo $content; ?>
        <!-- Right side -->

</html>
