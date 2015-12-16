<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$mainConfig = include dirname(__FILE__) . '/main.php';

return array(

    'basePath' => $mainConfig['basePath'],
    'name'     => $mainConfig['name'],

    // preloading 'log' component
    'preload'=>array('log'),
    'aliases'       => array(

    ),
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.rights.*',
        'application.modules.rights.models.*',
        'application.modules.rights.components.*',

    ),  
    // application components
    'components' => array(
        //'cache' => $mainConfig['components']['cache'],
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'connectionID'=>'db',
            'defaultRoles' => array('Guest'),
            'assignmentTable' => 'auth_assignment',
            'itemTable' => 'auth_item',
            'itemChildTable' => 'auth_item_child',
            'rightsTable' => 'rights',
            'showErrors'    => YII_DEBUG,
        ),
        'db'=>array(
                'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=fastreview',
                //'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=yarteam',
                'username' => 'service_user',
                'password' => '81352dC8cF12',
                'charset' => 'utf8',
                'enableProfiling' => YII_DEBUG,
                'enableParamLogging' => YII_DEBUG,
                'emulatePrepare' => false,
            ),

        ///'log'   => $mainConfig['components']['log'],
    ),
    'modules' => array(
        'users' => $mainConfig['modules']['users'],
        'rights' => $mainConfig['modules']['rights'],

    ),
    'commandMap'=>array(
        'stat' => array('class'=>'application.commands.StatCommand'),
      ),
);