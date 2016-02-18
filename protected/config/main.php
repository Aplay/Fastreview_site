<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
//Yii::setPathOfAlias('editable', dirname(__FILE__).'/../extensions/x-editable');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Быстрые отзывы',
    'theme'=>'material',
    'language' => 'ru',

    // preloading 'log' component
    'preload' => array('log'),



    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.helpers.*',
        'application.components.validators.*',
        'application.modules.core.models.*',
       

        'application.modules.users.*',
        'application.modules.users.models.*',
        'application.modules.users.components.*',

        'application.modules.rights.*',
        'application.modules.rights.models.*',
        'application.modules.rights.components.*',
        
        'application.modules.city.models.*',
        'application.modules.catalog.models.*',

        'application.modules.poll.models.*',
        'application.modules.poll.components.*',

       // 'application.modules.logger.models.*', 
        // 'application.modules.nfy.models.*',
       // 'application.modules.nfy.components.*',
        'ext.SlugHelper.SlugHelper',

    ),

    'modules' => array(
        'admin',
        'users' => array(
            'tableUsers' => 'users',
            'hash' => 'md5', // encrypting method (php hash function)
            'sendActivationMail' => false, // send activation email
            'loginNotActiv' => false, // allow access for non-activated users
            'activeAfterRegister' => true, // activate user on registration (only sendActivationMail = false)
            'autoLogin' => true, // automatically login from registration
            'registrationUrl' => array('/registration'), // registration path
            'recoveryUrl' => array('/recovery'), // recovery password path
            'returnLogoutUrl' => array('/login'), // page after logout
        ),
        'rights' => array(
            'superuserName' => 'Admin', // Name of the role with super user privileges.
            'authenticatedName' => 'Authenticated', // Name of the authenticated user role.
            'userIdColumn' => 'id', // Name of the user id column in the database.
            'userNameColumn' => 'username', // Name of the user name column in the database.
            'enableBizRule' => true, // Whether to enable authorization item business rules.
            'enableBizRuleData' => true, // Whether to enable data for business rules.
            'displayDescription' => true, // Whether to use item description instead of name.
            'flashSuccessKey' => 'RightsSuccess', // Key to use for setting success flash messages.
            'flashErrorKey' => 'RightsError', // Key to use for setting error flash messages.
            'baseUrl' => '/rights', // Base URL for Rights. Change if module is nested.
            'layout' => 'application.views.layouts.admin', // Layout to use for displaying Rights.
          //  'layout'=>'application.modules.admin.views.layouts.main',
            'cssFile'=>false,
            'appLayout' => 'application.views.layouts.admin', // Application layout.
            'install' => false, // Whether to enable installer.
            'debug' => YII_DEBUG,
        ),
        
        'core',
        'catalog',
        'city',
        'comments',
        'poll' => array(
           // Force users to vote before seeing results
           'forceVote' => TRUE,
           // Restrict anonymous votes by IP address,
           // otherwise it's tied only to user_id 
           'ipRestrict' => TRUE,
           // Allow guests to cancel their votes
           // if ipRestrict is enabled
           'allowGuestCancel' => FALSE,
         ),
        'file'

    ),
    // application components
    'components' => array(

        'db' => array(
            'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=fastreview',
            'username' => 'service_user',
            'password' => '81352dC8cF12',
            'charset'               => 'utf8',
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,
            'emulatePrepare'        => false,
            'schemaCachingDuration' => YII_DEBUG ? 0 : 3600,
        ),
        'cache'=>array(
            'class'=>'CFileCache',
        ),

        'clientScript' => array(

            'packages' => array(
                'jquery'=>array(
                    // 'baseUrl'=>'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js',
                     'baseUrl'=>'/themes/material/',
                  
                    'js'=>array(
                   
                    	'js/jquery-2.1.1.min.js',
                        'js/jquery.debouncedresize.js'
                        ),
                    ),
               'bootstrap' => array(
                     'baseUrl'=>'/themes/material/',
                    'js'=>array(
                    	'js/jquery-ui.min.js',
                        'js/bootstrap.min.js',
                        'vendors/sparklines/jquery.sparkline.min.js',
                      //  'vendors/waves/waves.min.js',
                        'vendors/bootstrap-growl/bootstrap-growl.min.js',
                        'vendors/sweet-alert/sweet-alert.min.js',
                       // 'vendors/nicescroll/jquery.nicescroll.min.js',
                        'js/functions.js',
                        'js/main.js',

                    ),
                    'css'=>array(
                        'css/bootstrap.min.css',
                        'font-awesome-4.5.0/css/font-awesome.min.css'

                    ),
                    'depends' => array('jquery'),
                ),

            ),
            'coreScriptPosition'=>CClientScript::POS_END
        ),
        'image' => array(
            'class' => 'ext.image.CImageComponent', // Библиотека для обрезки изображений
            'driver' => 'GD',
           // 'params' => array('directory' => '/opt/local/bin'),
        ),
        'imageHandler' => array(
            'class' => 'ext.CImageHandler', // Библиотека для работы с изображениями, в т.ч. WaterMark
        ),
        'user' => array(
            'class' => 'WebUser',
            'allowAutoLogin' => true,
          //  'loginUrl' => array('/login'),
         //   'returnUrl' => array('/dashboard'),
            'identityCookie' => array(
              'path' => '/',
              'domain' => '.'.SETTINGS_SERVERNAME,
              'httpOnly' => true
              ),
             'autoRenewCookie' => true
        ),
        'session' => array(
	       // 'savePath' => '/some/writeable/path',
            'timeout' => 86400,
	        'cookieMode' => 'allow',
	        'cookieParams' => array(
	            'path' => '/',
	            'domain' => '.'.SETTINGS_SERVERNAME,
	            'httpOnly' => true,
	        ),
	    ),
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles' => array('Guest'),
            'assignmentTable' => 'auth_assignment',
            'itemTable' => 'auth_item',
            'itemChildTable' => 'auth_item_child',
            'rightsTable' => 'rights',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                

                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',

                '/gotwitter' => '/site/gotwitter',
                '/authvk' => '/site/vklogin',
                '/authfb' => '/site/fblogin',
                '/authtw' => '/site/twlogin',

                'auth/admin/captcha'=>'admin/auth/captcha',

                SETTINGS_ADMINPATH =>'admin/default/index',
                SETTINGS_ADMINPATH.'/auth'=>'admin/auth',
                SETTINGS_ADMINPATH.'/auth/logout'=>'admin/auth/logout',


                /*
                SETTINGS_ADMINPATH.'/<module:\w+>'=>'<module>/admin/default',

                SETTINGS_ADMINPATH.'/<module:\w+>/<action:update|create|delete>/<id:\d+>'=>'<module>/admin/default/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<action:update|create|delete>'=>'<module>/admin/default/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>'=>'<module>/admin/<controller>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/admin/<controller>/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/admin/<controller>/<action>/<id>',

                SETTINGS_ADMINPATH.'/<controller:\w+>'=>'admin/<controller>',
                SETTINGS_ADMINPATH.'/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
                SETTINGS_ADMINPATH.'/<controller:\w+>/<action:\w+>/<id:\d+>'=>'admin/<controller>/<action>',
                */
                SETTINGS_ADMINPATH.'/<module:\w+>'=>'<module>/admin/default',

                SETTINGS_ADMINPATH.'/<module:\w+>/<action:update|create|delete>/<id:\d+>'=>'<module>/admin/default/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<action:update|create|delete>'=>'<module>/admin/default/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>'=>'<module>/admin/<controller>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/admin/<controller>/<action>',
                SETTINGS_ADMINPATH.'/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/admin/<controller>/<action>/<id>',

                 // pages:
                '/<page:about|legal|privacy>' => '/site/<page>',
                // site:
                
                '/<action:logout|feedback|captcha>' => '/site/<action>',
                
                '/<action:login|registration|recovery|profile>' => '/users/<action>',

                '/<action:new_object>' =>'/fastreview/<action>',

                '/search/*'=>'/fastreview/search',
                
                '/review_objects/*'=>'/site/review_objects',

                '/reviews/<id:\d+><dash:[-]><itemurl:[0-9A-Za-z_-]+>' => array('/fastreview/item', 'caseSensitive'=>false),

                array('fastreview/view', 'pattern'=>'/reviews/<url>/*'),
                array('fastreview/view', 'pattern'=>'/reviews/*'),
                '/user/<url:[0-9A-Za-z_]+>'=>'/users/user/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>/'=>'<module>/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ),
        ),
        'mail'=>array(
            'class'=>'ext.mailer.EMailer',
            'CharSet'=>'UTF-8',
            'Encoding'=>'base64'
        ),
        'request'=>array(
           'class'=>'SHttpRequest',
            'enableCsrfValidation'=>true,
            'enableCookieValidation'=>true,
            'noCsrfValidationRoutes'=>array(),
          //  'csrfTokenName'=>'HiddenPropertyValue',
            'csrfCookie' => array(
            	'path' => '/',
	            'domain' => '.'.SETTINGS_SERVERNAME,
	            'httpOnly'=>true
	        ),
        ),
        'errorHandler' => array(
            'errorAction'=>'site/error',
        ),
        'mobileDetect' => array(
            'class' => 'ext.MobileDetect.MobileDetect.MobileDetect'
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
            	array(
                  //  'class' => 'CFileLogRoute',
                    'class' => 'AdvancedEmailLogRoute',
                    'filter' => array(
                        'class'=>'AdvancedLogFilter',
                        'ignoreCategories' => array(
                            // Ignore 404s
                            'exception.CHttpException.404',
                            ),
                        ),
                    'levels'=>'error, warning',
                    'emails' => array('makarenok.roman@gmail.com'),
                    'sentFrom' => 'info@'.SETTINGS_SERVERNAME,
                    'subject' => 'Error at '.SETTINGS_SERVERNAME,
                    'utf8' => true
                ),

            ),

        ),
		'vkApi' => array(
            'class' => 'vkApi',
            'clientId' => '5199324',
            'clientSecret' => 'X4htSSJmUQ1CV9SOoG1y',
            'redirectUri' => 'http://'.SETTINGS_SERVERNAME.'/authvk',
        ),
        'fbApi' => array(
            'class' => 'fbApi',
            'clientId' => '1052949411392044',
            'clientSecret' => '00c8745e03bca3a6fc5872938774d4b4',
            'redirectUri' => 'http://'.SETTINGS_SERVERNAME.'/authfb',
        ),
        'twApi' => array(
                'class' => 'twApi',
                'passUrl' => '/gotwitter'
            ),
        'twitter' => array(
                'class' => 'ext.yiitwitteroauth.YiiTwitter',
                'consumer_key' => '3QbOTif6za9JbYNcD4Lme8SGc',
                'consumer_secret' => 'LkKbXXTbe82oMAvMq7NKiykRiHBbX3tbqvFZrVUesuCua7tmAX',
                'callback' => 'http://'.SETTINGS_SERVERNAME.'/authtw',

            ),
        'reCaptcha'=>array(
        	'class'=>'ReCaptcha',
        	'siteKey'=>'6LcsZAoTAAAAAGpdfm2wCl3dspPMvd8XEYHHbvOM',
        	'secret'=>'6LcsZAoTAAAAANSqKiQCRXrTWxiZqS8l99vlXMyZ'
        ),
        'portlet'=>array(
            'class'=>'application.components.Portlet',
        ),
    ),
    

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'adminEmail' => 'makarenok.roman@gmail.com',
        'adminPath'=>SETTINGS_ADMINPATH,
        'serverName'=>SETTINGS_SERVERNAME,
        'imageExtentions'=>array('jpg','gif','png','jpg2'),
         'storeImages'=>array(
                        'dirMode' =>'0755', // need 0755 warning! in chmode don't forget to do octdec($dirMode)
                        'fileMode' =>'0644',
                        'assets'    => 'webroot.assets',
                        'uploads'=>'webroot.uploads',
                        'tmp'        => 'webroot.uploads.tmp',
                        'thumbPath'   => 'webroot.assets.thumbs',
                        'thumbUrl'    => '/assets/thumbs/', // With ending slash
                        'maxFileSize' => 5*1024*1024, // 5mb
                        'maxFileSizeAvatar' => 2*1024*1024, // 5mb
                        'sizes'=>array(
                            'resizeMethod'=>'resize', // resize/adaptiveResize
                            'resizeThumbMethod'=>'adaptiveResize', // resize/adaptiveResize ( прямоугольник режем до квадрата )
                            'maximum'=>array(1000, 1000), // All uploaded images
                        ),
                        'thumbFolders'=>array('1000','800','640','500','300','160'),
        ),
         'mime_fileview'=>array('image/gif','image/jpeg','image/png','application/pdf'),
         'mime_fileview_microsoft'=>array('application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','text/rtf')
    ),
);
