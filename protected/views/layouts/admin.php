<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        'js/plugins/jquery_ui_custom_1.11.1/jquery-ui.min.js',
                        
                        'js/pixel-admin_modern.js',
                      // 'js/jquery-ui-extras.min.js',
                        'js/fastclick.js',
                        'js/plugins/vague/Vague.js',
                        'js/dropzone.js',
                        'js/plugins/select2-3.5.1/select2.min.js',
                        
                       // 'js/plugins/dataTables/js/jquery.dataTables.min.js',
                        'js/main.js'
                       // 'js/demo.js'
                    ),
                    'css'=>array(
						'css/bootstrap.min.css',
						'css/pixel-admin.css',
                        'css/widgets.css',
                        'css/themes.css',
                        'css/pages.css',
                        'css/style.css',

                    ),
                    'depends'=>array('jquery'),
 
                );

	$cs->addPackage('bootstrap', $assetsPackage);
    $cs->registerPackage('bootstrap');

    
    ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
  <!--

TABLE OF CONTENTS.

Use search to find needed section.

===================================================================

|  1. $BODY                        |  Body                        |
|  2. $MAIN_NAVIGATION             |  Main navigation             |
|  3. $NAVBAR_ICON_BUTTONS         |  Navbar Icon Buttons         |
|  4. $MAIN_MENU                   |  Main menu                   |
|  5. $UPLOADS_CHART               |  Uploads chart               |
|  6. $EASY_PIE_CHARTS             |  Easy Pie charts             |
|  7. $EARNED_TODAY_STAT_PANEL     |  Earned today stat panel     |
|  8. $RETWEETS_GRAPH_STAT_PANEL   |  Retweets graph stat panel   |
|  9. $UNIQUE_VISITORS_STAT_PANEL  |  Unique visitors stat panel  |
|  10. $SUPPORT_TICKETS            |  Support tickets             |
|  11. $RECENT_ACTIVITY            |  Recent activity             |
|  12. $NEW_USERS_TABLE            |  New users table             |
|  13. $RECENT_TASKS               |  Recent tasks                |

===================================================================

-->  
  </head>
<!-- 1. $BODY ======================================================================================
    
    Body

    Classes:
    * 'theme-{THEME NAME}'
    * 'right-to-left'      - Sets text direction to right-to-left
    * 'main-menu-right'    - Places the main menu on the right side
    * 'no-main-menu'       - Hides the main menu
    * 'main-navbar-fixed'  - Fixes the main navigation
    * 'main-menu-fixed'    - Fixes the main menu
    * 'main-menu-animated' - Animate main menu
-->
<body class="theme-frost main-menu-animated main-navbar-fixed main-menu-fixed">

<?php
$scriptAdd = "
var init = [];
";
?>

<div id="main-wrapper">


<!-- 2. $MAIN_NAVIGATION ===========================================================================

    Main navigation
-->
    <div id="main-navbar" class="navbar navbar-inverse" role="navigation">
        <!-- Main menu toggle -->
        <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text"><?php echo Yii::t('site','Hide menu'); ?></span></button>
        
        <div class="navbar-inner">
            <!-- Main navbar header -->
            <div class="navbar-header">

                <!-- Logo -->
                <a href="/" class="navbar-brand">
                    <div><img alt="<?php echo Yii::app()->name; ?>" src="<?php echo $themeUrl; ?>/img/pixel-admin/main-navbar-logo.png"></div>
                    <?php echo Yii::app()->name; ?>
                </a>

                <!-- Main navbar toggle -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

            </div> <!-- / .navbar-header -->

            <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
                <div>
                    
                    <ul class="nav navbar-nav">
                    <li class="dropdown" id="main-navbar-create-stuff">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                                <button style="width: 160px;" class="btn btn-primary btn-flaty btn-labeled">
                                    <span class="btn-label icon fa fa-plus"></span>Добавить
                                </button>
                                </a>
                             <ul class="dropdown-menu">
                                
                                <li><a href="<?php echo Yii::app()->createUrl('/catalog/admin/create'); ?>"><span class="fa fa-tasks"></span> &nbsp;&nbsp;Рубрику</a></li>
                                <li><a href="<?php echo Yii::app()->createUrl('/catalog/admin/objects/create'); ?>"><span class="fa fa-user"></span> &nbsp;&nbsp;Объект</a></li>
                                <li><a href="<?php echo Yii::app()->createUrl('/poll/admin/poll/create'); ?>"><span class="fa fa-thumbs-o-up"></span> &nbsp;&nbsp;Голосование</a></li>
                                <li><a href="<?php echo Yii::app()->createUrl('/users/admin/create'); ?>"><span class="fa fa-user"></span> &nbsp;&nbsp;Пользователя</a></li>

                                </ul>
                           
                    </li>
                        
                    </ul> <!-- / .navbar-nav -->

                

                    <div class="right clearfix">
                        <ul class="nav navbar-nav pull-right right-navbar-nav">

                            <li>
                                <?php
                               // $this->widget('SearchBlock', array());
                                ?>
                            </li>

                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                    <img src="<?php echo Yii::app()->user->getAvatar(); ?>" alt="">
                                    <span><?php echo Yii::app()->user->username; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                <?php 
                               // echo CHtml::tag('li', array(), CHtml::link('<i class="dropdown-icon fa fa-envelope"></i>&nbsp;&nbsp;'.Yii::t('site','Message'), '#'));
                                ?>
                                    <li><a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>Yii::app()->user->username)); ?>"><i class="dropdown-icon fa fa-user"></i>&nbsp;&nbsp;<?php echo Yii::t('site','Profile'); ?></a></li>
                                    <li><a href="/settings"><i class="dropdown-icon fa fa-cog"></i>&nbsp;&nbsp;<?php echo Yii::t('site','Settings'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="/logout"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;<?php echo Yii::t('site','Log Out'); ?></a></li>
                                </ul>
                            </li>
                        </ul> <!-- / .navbar-nav -->
                    </div> <!-- / .right -->
                </div>
            </div> <!-- / #main-navbar-collapse -->
        </div> <!-- / .navbar-inner -->
    </div> <!-- / #main-navbar -->
<!-- /2. $END_MAIN_NAVIGATION -->


<!-- 4. $MAIN_MENU =================================================================================

        Main menu
        
        Notes:
        * to make the menu item active, add a class 'active' to the <li>
          example: <li class="active">...</li>
        * multilevel submenu example:
            <li class="mm-dropdown">
              <a href="#"><span class="mm-text">Submenu item text 1</span></a>
              <ul>
                <li>...</li>
                <li class="mm-dropdown">
                  <a href="#"><span class="mm-text">Submenu item text 2</span></a>
                  <ul>
                    <li>...</li>
                    ...
                  </ul>
                </li>
                ...
              </ul>
            </li>
-->
    <div id="main-menu" role="navigation">
        <div id="main-menu-inner">
            <div class="menu-content top" id="menu-content-demo">
                <!-- Menu custom content demo
                     CSS:        styles/pixel-admin-less/demo.less or styles/pixel-admin-scss/_demo.scss
                     Javascript: html/assets/demo/demo.js
                 -->
                <div>
                    <div class="text-bg"><span class="text-slim"><?php //echo Yii::t('site','Welcome,'); ?></span> <span class="text-semibold"><?php echo Yii::app()->user->username; ?></span></div>

                    <img src="<?php echo Yii::app()->user->getAvatar(); ?>" alt="">
                    <div class="btn-group">
                        <?php
                      //  echo CHtml::link('<i class="fa fa-envelope"></i>', '#', array('class'=>'btn btn-xs btn-primary btn-outline dark'));
                        ?>        
                        <a href="<?php echo Yii::app()->createUrl('/users/user/view', array('url'=>Yii::app()->user->username)); ?>" class="btn btn-xs btn-primary btn-outline dark"><i class="fa fa-user"></i></a>
                        <a href="/settings" class="btn btn-xs btn-primary btn-outline dark"><i class="fa fa-cog"></i></a>
                        <a href="/logout" class="btn btn-xs btn-danger btn-outline dark"><i class="fa fa-power-off"></i></a>
                    </div>
                    <a href="#" class="close">&times;</a>
                </div>
            </div>
            <ul class="navigation">
                <li <?php if($this->active_link == 'dashboard'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('admin/default/index'); ?>"><i class="menu-icon fa fa-dashboard"></i><span class="mm-text"><?php echo Yii::t('site','Dashboard'); ?></span></a>
                </li>
                <li <?php if($this->active_link == 'catalog'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/default'); ?>"><i class="menu-icon fa fa-tasks"></i><span class="mm-text">Рубрики</span></a>
                </li>
                <li <?php if($this->active_link == 'attribute'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/attribute'); ?>"><i class="menu-icon fa fa-list-ul"></i><span class="mm-text">Атрибуты</span></a>
                </li>
                <li <?php if($this->active_link == 'new_objects'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/objects/new_objects'); ?>"><i class="menu-icon fa fa-briefcase"></i><span class="mm-text">Новые объекты</span></a>
                </li>
                 <li <?php if($this->active_link == 'objects'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/objects'); ?>"><i class="menu-icon fa fa-briefcase"></i><span class="mm-text">Объекты</span></a>
                </li>
                <li <?php if($this->active_link == 'review'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('comments/admin/default'); ?>"><i class="menu-icon fa fa-comment"></i><span class="mm-text">Отзывы</span></a>
                </li>
                <li <?php if($this->active_link == 'new_article'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/article/new_article'); ?>"><i class="menu-icon fa fa-file-text-o"></i><span class="mm-text">Новые обзоры</span></a>
                </li>
                <li <?php if($this->active_link == 'article'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('catalog/admin/article'); ?>"><i class="menu-icon fa fa-file-text-o"></i><span class="mm-text">Обзоры</span></a>
                </li>
                <li <?php if($this->active_link == 'poll'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('poll/admin/poll'); ?>"><i class="menu-icon fa fa-thumbs-o-up"></i><span class="mm-text">Голосование</span></a>
                </li>
                <li <?php if($this->active_link == 'users'){ echo ' class="active"'; } ?>>
                    <a href="<?php echo Yii::app()->createUrl('users/admin/default'); ?>"><i class="menu-icon fa fa-users"></i><span class="mm-text"><?php echo Yii::t('site','Users'); ?></span></a>
                </li>
                
            </ul> <!-- / .navigation -->
            
        </div> <!-- / #main-menu-inner -->
    </div> <!-- / #main-menu -->
<!-- /4. $MAIN_MENU -->

    <div id="content-wrapper">

    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'tagName'=>'ul', // will change the container to ul
            'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>', // will generate the clickable breadcrumb links 
            'inactiveLinkTemplate'=>'<li class="active">{label}</li>', // will generate the current page url : <li>News</li>
            'homeLink'=>'<div class="breadcrumb-label text-light-gray">'.Yii::t('site','You are here:').' </div><li><a href="'.Yii::app()->createAbsoluteUrl('admin/default/index').'">'.Yii::t('site','Dashboard').'</a></li>',
            'htmlOptions'=>array('class'=>'breadcrumb breadcrumb-page'),
            'separator'=>' '
        ));
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php echo $content; ?>
        </div>
    </div>
    </div> <!-- / #content-wrapper -->
    <div id="main-menu-bg"></div>
</div> <!-- / #main-wrapper -->


<?php
$scriptAdd .= "
    init.push(function () {
        // Javascript code here
    });

    window.PixelAdmin.start(init, {
        'is_mobile': /iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()),
        'main_menu':{
            'store_state':true,
            'disable_animation_on':['desktop','small','tablet'],
            'detect_active':false,
        }
    }
    );
    

";
Yii::app()->clientScript->registerScript("pxTeam", $scriptAdd, CClientScript::POS_END);
?>
<input type="hidden" id="csfr" name="<?php echo Yii::app()->request->csrfTokenName; ?>" value="<?php echo Yii::app()->request->csrfToken; ?>" />
</body>
</html>