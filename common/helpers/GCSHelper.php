<?php
namespace common\helpers;

use Yii;
use yii\web\UploadedFile;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\Exception\GoogleException;

class GCSHelper {
    /**
     * image filetype
     * @var UploadFile $file
     */
    public static function isImage($file){
        $type = $file->type;
        if (!in_array($type,['image/png','image/jpeg','image/gif','image/bmp'])){
            return false;
        }
        return true;
    }
    /**
     * filepath
     */
    public static function getFilePath($school_id,$class_id,$module_id){
        return 'school_'.$school_id.'/'.'class_'.$class_id.'/'.'module_'.$module_id;
    }

    /**
     * filename
     * @var UploadFile $file
     */
    public static function getFilename($file){
        return date('YmdHis') . '_' . strtolower(preg_replace('/\s+/', '', trim($file->name)));
    }

    /**
     * upload file to GCS
     * @var string $bucket
     * @var string $filepath
     * @var UploadFile $file
     */
    public static function upload($bucket,$filepath,$file){       
        $storage = new StorageClient([
            'projectId' => getenv('GCP_PROJECT_ID'),
            'keyFilePath' => getenv('GCP_SERVICE_ACCOUNT_KEY'),
        ]);
        

        try{
            $bucket = $storage->bucket($bucket);
            $filename = static::getFilename($file);
            $filename = $filepath.'/'.$filename;
            $object = $bucket->object($filename);
            if ($object->exists()){
                // delete existing object
                $object->delete();
            }

            $bucket->upload(fopen($file->tempName,'r'),['name' => $filename]);

            $object = $bucket->object($filename);

            $duration = 15 * 60;
            return $object->signedUrl(new \DateTime('+ ' . $duration . ' seconds'));
        }catch(GoogleException $e){
            Yii::error('upload file failed with error',$e->getMessage());
            //print_r($e->getMessage());die;
            return false;
        }
    }
}