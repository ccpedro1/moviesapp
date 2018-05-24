<?php

use \yii\web\Request;

$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());


$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'enableConfirmation' => false,
            'enableRegistration'=> false, 
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['admin']
        ],
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
    ],
    'language' => 'pt-BR',
    'sourceLanguage' => 'en',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wtwtrKQieIf2ofijjwS9PgDPC3P9Yph0',
            'baseUrl' => $baseUrl,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        //Necessário para evitar erro (Unable to locate message source for category 'rbac-admin'.) no módulo. 
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'ccpedro@gmail.com',
                'password' => '',
                'port' => '587',
                'encryption' => 'tls',
            ],
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'baseUrl' => $baseUrl,
            'rules' => [
                'autores' => 'autor/index',
                '<controller>s' => '<controller>/index',
                '<controller>/<id:\d+>' => '<controller>/view',
            ],
        ],
        // Sobrescrever as views de user do Módulo dektrium/user, lembrando de manter o caminho exato que estiver 
        // no módulo. EX: vendor/dektrium/yii2-user/views/security/login.php => views/user/security/login.php
        'view' => [
            'theme' => [
                    'pathMap' => [
                        '@dektrium/user/views' => '@app/views/user'
                    ]
                ]
            ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
