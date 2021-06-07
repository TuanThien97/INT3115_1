<?php
namespace api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends \yii\rest\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post'],
                ],
            ],
            'response' => [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /** 
     * @return Schools
     */
    public function actionIndex()
    {
        return ['code' => 0, 'message' => 'index'];
    }

    /** 
     * @return Schools
     */
    public function actionLogin()
    {
        return ['code'=>0,'message'=>'login'];
    }

    /** 
     * @return Schools
     */
    public function actionError()
    {
        return ['code' => -500, 'message' => 'error'];
    }
}
