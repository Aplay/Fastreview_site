<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'),
    array(
        'modules' => array(
            'gii' => array(
                'class'=>'system.gii.GiiModule',
                'password'=>false,
                'ipFilters'=>array('127.0.0.1','::1'),
                ),
        ), 
        'components' => array(
            'db'=>array(
                'connectionString' => 'pgsql:host=127.0.0.1;port=5432;dbname=fastreview_local',
                //'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=yarteam',
                'username' => 'postgres',
                'password' => '12345',
                'charset' => 'utf8',
                'enableProfiling' => YII_DEBUG,
                'enableParamLogging' => YII_DEBUG,
                'emulatePrepare' => false,
                'schemaCachingDuration' => 3600, // не забываем, если в табличку занесли новые поля, чистить кэш
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                  /*  array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),*/
                    /*
                    array(
                        'class'=>'CProfileLogRoute',
                        'enabled'=>true,
                    ),
                    */
                    // uncomment the following to show log messages on web pages
                    array(
                        'class'=>'CWebLogRoute',
                        'levels' => 'error, warning',
                    ), 
                   /* array(
                    'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters'=>array('127.0.0.1')
                    )*/
                    
                    
                   
                ),
            ),
        ),
    )
);
