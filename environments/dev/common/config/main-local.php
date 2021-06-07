<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . getenv('POSTGRES_DB_HOST') . ';dbname=' . getenv('POSTGRES_DB_APP'),
            'username' => getenv('POSTGRES_DB_USER'),
            'password' => getenv('POSTGRES_DB_PASSWORD'),
            'charset' => 'utf8',
            'schemaMap' => [
                'pgsql' => [
                    'class' => 'yii\db\pgsql\Schema',
                    'defaultSchema' => 'public' //specify your schema here
                ]
            ],
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("SET TIMEZONE TO 'Asia/Saigon';")->execute();
            },
        ],
        'db_auth' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . getenv('POSTGRES_DB_HOST') . ';dbname=' . getenv('POSTGRES_DB_AUTH'),
            'username' => getenv('POSTGRES_DB_USER'),
            'password' => getenv('POSTGRES_DB_PASSWORD'),
            'charset' => 'utf8',
            'schemaMap' => [
                'pgsql' => [
                    'class' => 'yii\db\pgsql\Schema',
                    'defaultSchema' => 'public' //specify your schema here
                ]
            ],
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("SET TIMEZONE TO 'Asia/Saigon';")->execute();
            },
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => 0,
        ], 
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'onesignal' => [
            'class' => 'common\components\OneSignal',
            'app_id_teacher' => getenv('ONESIGNAL_APP_ID_TEACHER'),
            'api_key_teacher' => getenv('ONESIGNAL_API_KEY_TEACHER'),
            'app_id_parent' => getenv('ONESIGNAL_APP_ID_PARENT'),
            'api_key_parent' => getenv('ONESIGNAL_API_KEY_PARENT'),
            'app_id_principal' => getenv('ONESIGNAL_APP_ID_PRINCIPAL'),
            'api_key_principal' => getenv('ONESIGNAL_API_KEY_PRINCIPAL'),
        ]
    ],
];
