<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'mouse',
    'basePath' => dirname(__DIR__),
    'language' => 'vi',
    'timeZone' => 'Asia/Saigon', 
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'common\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'common\modules\v2\Module',
        ],
        'v3' => [
            'class' => 'common\modules\v3\Module',
        ],
        'v4' => [
            'class' => 'common\modules\v4\Module',
        ],
        'v5' => [
            'class' => 'common\modules\v5\Module',
        ],
        'v6' => [
            'class' => 'common\modules\v6\Module',
        ],
        'v7' => [
            'class' => 'common\modules\v7\Module',
        ],
        'v8' => [
            'class' => 'common\modules\v8\Module',
        ],
        'v9' => [
            'class' => 'common\modules\v9\Module',
        ],
        'v10' => [
            'class' => 'common\modules\v10\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api-teacher-v1',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\models\Teachers',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-api-teacher-v1', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'api-v1',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'except' => [
                        'yii\db\*'
                    ],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                        'app/notification' => 'notification.php',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'db' => 'db_auth',
            'itemTable' => 'api_teacher_auth_item',
            'itemChildTable' => 'api_teacher_auth_item_child',
            'ruleTable' => 'api_teacher_auth_rule',
            'assignmentTable' => 'api_teacher_auth_assignment',
        ],
        'apiTeacherAuthManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'db' => 'db_auth',
            'itemTable' => 'api_teacher_auth_item',
            'itemChildTable' => 'api_teacher_auth_item_child',
            'ruleTable' => 'api_teacher_auth_rule',
            'assignmentTable' => 'api_teacher_auth_assignment',
        ],
        'apiParentAuthManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'db' => 'db_auth',
            'itemTable' => 'api_parent_auth_item',
            'itemChildTable' => 'api_parent_auth_item_child',
            'ruleTable' => 'api_parent_auth_rule',
            'assignmentTable' => 'api_parent_auth_assignment',
        ],
        'apiPrincipalAuthManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'db' => 'db_auth',
            'itemTable' => 'api_principal_auth_item',
            'itemChildTable' => 'api_principal_auth_item_child',
            'ruleTable' => 'api_principal_auth_rule',
            'assignmentTable' => 'api_principal_auth_assignment',
        ],
        'jwt' => [
            'class' => 'sizeg\jwt\Jwt',
            'key'   => 'aisuBE3tEne91bIdeu4ReSK',
        ],
        'gcs' => [
            'class' => 'common\components\GCSManager',
            'signed_url_duration' => 15*60,
        ],
        'megaid' => [
            'class' => 'common\components\megaid\MegaID',
            'endpoint' => getenv('MEGAID_ENDPOINT'),
            'version' => getenv('MEGAID_VERSION'),
            'accessKey' => getenv('MEGAID_ACCESS_KEY'),
            'secretKey' => getenv('MEGAID_SECRET_KEY'),
            'service' => getenv('MEGAID_SERVICE'),
            'serviceApp' => getenv('MEGAID_SERVICE_APP'),
        ],
        'megapay' => [
            'class' => 'common\components\megapay\MegaPay',
            'endpoint' => getenv('MEGAPAY_ENDPOINT'),
            'version' => getenv('MEGAPAY_VERSION'),
            'accessKey' => getenv('MEGAPAY_ACCESS_KEY'),
            'secretKey' => getenv('MEGAPAY_SECRET_KEY'),
            'service' => getenv('MEGAPAY_SERVICE'),
            'serviceApp' => getenv('MEGAPAY_SERVICE_APP'),
        ],
        'authrbac' => [
            'class' => 'common\components\AuthRbac',
        ],
    ],
    'params' => $params,
    // 'as access' => [
    //     //'class' => 'mdm\admin\components\AccessControl',
    //     'class' => 'common\components\GateKeeper',
    //     'allowActions' => [
    //         'site/*',
    //         'admin/*',
    //         //'some-controller/some-action',
    //         'gii/*',
    //         // The actions listed here will be allowed to everyone including guests.
    //         // So, 'admin/*' should not appear here in the production, of course.
    //         // But in the earlier stages of your development, you may probably want to
    //         // add a lot of actions here until you finally completed setting up rbac,
    //         // otherwise you may not even take a first step.
    //     ]
    // ],
];
