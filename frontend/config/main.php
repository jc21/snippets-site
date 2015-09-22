<?php
$params = array_merge(
    require (__DIR__ . '/../../common/config/params.php')
);

return [
    'id'                  => 'snippets-app',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [],
    'components'          => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'omgisthissnippetsreal',
        ],
        'urlManager' => array(
            'class'           => 'yii\web\UrlManager',
            'showScriptName'  => false,
            'enablePrettyUrl' => true,
            'rules'           => array(
                'snippets/<languageSlug:[\w\-]+>'                       => 'snippets/index',
                'snippets/<languageSlug:[\w\-]+>/<snippetSlug:[\w\-]+>' => 'snippets/index',
                'snippets/<languageSlug:[\w\-]+>/<snippetSlug:[\w\-]+>/<action:\w+>' => 'snippets/<action>',
                'lists/<action:\w+>/<languageSlug:[\w\-]+>'             => 'lists/<action>',
                '<controller:\w+>/<id:\d+>'                             => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'                => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'                         => '<controller>/<action>',
            ),
        ),
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
