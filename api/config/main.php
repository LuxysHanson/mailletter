<?php
$params = array_merge(
    require __DIR__ . '/../../config/constants.php',
    require __DIR__ . '/../../config/params.php'
);

return [
    'id' => 'basic-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'aliases' => [
//        '@api' => dirname(__DIR__),
    ],
    'modules' => [],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ],
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter'
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => false,
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => array_merge(
                require __DIR__ . '/rules.php'
            ),
        ]
    ],
    'params' => $params,
];