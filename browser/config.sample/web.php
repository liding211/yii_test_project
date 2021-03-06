<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                'project/<username:[a-zA-Z0-9 \-_.]+>/<repository:[a-zA-Z0-9 \-_.]+>' => 'site/project',
                'user/<username:[a-zA-Z0-9 \-_.]+>' => 'site/user',
                '<action:[a-zA-Z\-_.]+>' => 'site/<action>',
                '<controller:\w+>/<action:[a-zA-Z\-_.]+>' => '<controller>/<action>',
            ),
        ],
        'github_client' => [
            'class' => 'Github\Client'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_imGlV1iDL83ijg68I_-xFGa6xACxllM',
            'enableCsrfValidation' => true,
        ],
        'response' => [
            'class' => 'yii\web\Response',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

@define(GITHUB_API_LOGIN,     'github_user_login'); 
@define(GITHUB_API_PASSWORD,  'github_user_password');

@define(MAIN_PAGE_PROJECT_USERNAME, 'yiisoft');
@define(MAIN_PAGE_PROJECT_REPOSITORY, 'yii2'); 

return $config;
