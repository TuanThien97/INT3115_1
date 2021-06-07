<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (defined('YII_ENV') && YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'generators' => [ //here
            'model' => [ // generator name
                'class' => '\common\templates\model\Generator', // generator class
                'templates' => [ //setting for out templates
                    'myCrud' => '@common/templates/model/default', // template name => path to template
                ]
            ]
        ],
    ];
}

return $config;
