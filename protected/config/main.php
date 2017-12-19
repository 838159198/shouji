<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$date = date('Y-m-d');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'系统',
    'language' => 'zh_cn',
    'timeZone' => 'PRC',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
    'modules'=>array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'dhadmin',//管理后台
        'member',//会员中心
        'ditui',//会员中心
        'newdt',//会员中心
        'dealer',
		'weixin',
    ),
	'defaultController'=>'site',

	// application components
	'components'=>array(
        'session'=>array(
            'timeout'=>864000
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'authTimeout'     => 864000,
		),
		/*'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=shouji',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'tablePrefix' => 'app_',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'appendParams' => false,
            'showScriptName' => false,
			'rules'=>array(
                '<serial_number:\d+>'=>'androidApi/GetCodeurl',
				'ditui' => 'ditui/default/index',
                'newdt' => 'newdt/default/index',
                //首页
                '/'=>'site/index',
                '2017year'=>'yearendDraw/index',
                /*注册登录*/
                'login'=>'site/login',
                'reg'=>'site/reg',
                /*产品*/
                'product'=>'product/index',
                // 推广链接简化
                't/<t:\d+>'=>'domains/in',
                /*页面*/
                'inc/<pathname:\w+>'=>'page/detail',
                /*文章*/
                'article/<id:\d+>'=>'article/detail',
                'article/p<page:\d+>'=>'article/index',
                'article/<pathname:\w+>/p<page:\d+>'=>'article/list',
                'article/<pathname:\w+>'=>'article/list',

                'article'=>'article/index',
                /*活动页面*/
                'hd/zzysjs/<periods:\w+>'=>'/hd/zzysjs',
                /*会员中心*默认首页*/
                'member' => 'member/default/index',
                /*管理后台*默认首页*/
                'dhadmin' => 'dhadmin/default/index',
                /*管理后台*默认首页*/
                'dealer' => 'dealer/default/index',
                /*内容：公告*/
                '<catepathname:\w+>/<id:\d+>'=>'post/detail',
                '<pathname:\w+>/p<page:\d+>'=>'post/category',
                '<pathname:\w+>'=>'post/category',




				/*'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',*/

                //通用
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

			),
		),
        //cache数据缓存，用于页面数据缓存
        'cache' => array(
            'class' => 'CFileCache',
            'directoryLevel' => 2,
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
                //回收业务资源
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'recycle-' . $date . '.log',
                    'categories' => 'recycle',
                ),
                //封号
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'closeaccount-' . $date . '.log',
                    'categories' => 'closeaccount',
                ),
                //xdebug 配置
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),

                //修改用户信息
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'editmember-' . $date . '.log',
                    'categories' => 'editmember',
                ),
                //修改用户单价
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'member-resource-price-' . $date . '.log',
                    'categories' => 'memberResourcePrice',
                ),
                //用户动作记录
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'member-event-' . $date . '.log',
                    'categories' => 'memberEvent',
                ),
                //用户注册发送手机号
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'member-event-' . $date . '.log',
                    'categories' => 'register',
                ),
                //管理员动作记录
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'info-' . $date . '.log',
                    'categories' => 'info',
                ),
                //error
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'error-' . $date . '.log',
                    'categories' => 'error',
                ),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);