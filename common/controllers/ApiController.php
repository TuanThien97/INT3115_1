<?php
namespace common\controllers;

use Yii;
use yii\rest\Controller;

class ApiController extends Controller{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //$behaviors = parent::behaviors();

        $behaviors = [
            'format' => [
                'class' => \yii\filters\ContentNegotiator::className(),
                //'only' => ['index', 'view'],
                'except' => ['upload-photo'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'test'  => ['GET'],
                    '*' => ['POST'],
                ],
            ],
            'auth' => [
                'class' => \common\components\ApiAuth::class,
                'optional' => [
                    'test'
                ],
                'allowRoutes' => [
                    '/user/*'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * echo test
     * @return string
     */
    public function actionTest()
    {
        return ["code" => 0, "message" => "ok"];
    }
}