<?php

namespace common\modules\v10\controllers;

use Yii;
use yii\rest\Controller;
use Google\Cloud\Storage\StorageClient;

/**
 * Default controller for the `v2` module
 */
class DefaultController extends \common\controllers\ApiController
{
    /**
     * get signed URL for upload files to GCS
     */
    public function actionSignedUrl(){
        $storageClient = new StorageClient([
            'projectId' => 'aischool-prod',
            'keyFilePath' => getenv('GCP_SERVICE_ACCOUNT_KEY'),
        ]);
        $bucket = $storageClient->bucket('aischool_public');
        $object = $bucket->object('logo.png');
        $duration = 15*60;
        $url = $object->signedUrl(new \DateTime('+ ' . $duration . ' seconds'));
        // $url = $object->beginSignedUploadSession([
        //     'contentType' => 'image/png'
        // ]);

        // if ($object->exists()) {
        //     echo 'Object exists!';
        // }else{
        //     echo 'Object not exists!';
        // }

        return $url;
        //return 1;
    }

    /**
     * test push notification
     */
    public function actionTest(){
        //echo Yii::t('app/notification', 'Your kid has been checked in');
        Yii::$app->onesignal->sendNotification(1,['title'=>'test','content'=>'send from server','data'=>['type'=>'checkin']],Yii::$app->onesignal->app_parent);
        return 1;
    }
}
