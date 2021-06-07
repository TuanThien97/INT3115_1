<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\Exception\GoogleException;
// models
use common\models\Photos;

class GCSManager extends Component {
    public $signed_url_duration = 60*60;
    public $max_file_size = 5*1024*1024;

    /**
     * image filetype
     * @var UploadFile $file
     */
    public function isImage($file)
    {
        $type = $file->type;
        if (!in_array($type, ['image/png', 'image/jpeg', 'image/gif', 'image/bmp'])) {
            return false;
        }
        return true;
    }
    /**
     * filepath
     */
    public function getFilePath($school_id, $class_id, $module_id)
    {
        if ($class_id) {
            return 'school_' . $school_id . '/' . 'class_' . $class_id . '/' . 'module_' . $module_id;
        } else {
            return 'school_' . $school_id . '/' . 'module_' . $module_id;
        }
    }

    /**
     * filename
     * @var UploadFile $file
     */
    public function getFilename($file)
    {
        if (is_object($file) && $file->name){
            return date('YmdHis') . '_' . strtolower(preg_replace('/\s+/', '', trim($file->name)));
        }
        return date('YmdHis') . '_' . strtolower(preg_replace('/\s+/', '', trim($file)));
    }

    /** 
     * validate file
     * @var UploadFile $file
     */
    public function validate($file,$type='image'){
        Yii::info('file uploaded::'.json_encode($file));
        if ($type=='image'){
            if (!$this->isImage($file)) {
                $result = [
                    'name' => 'upload file',
                    'code' => -2,
                    'status' => 402,
                    'message' => 'file invalid, must be image filetype',
                ];
                return $result;
            }
            if ($file->size > $this->max_file_size) {
                $result = [
                    'name' => 'upload file',
                    'code' => -1,
                    'status' => 400,
                    'message' => 'file to big, max size is ' . $this->max_file_size,
                ];
                return $result;
            }
            return null;
        }else{
            return null;
        }
    }

    /**
     * upload file to GCS
     * @var string $bucketName
     * @var string $filepath
     * @var UploadFile $file
     * @var array $data
     */
    public function upload($bucketName, $filepath, $file, $data = null)
    {
        $storage = new StorageClient([
            'projectId' => getenv('GCP_PROJECT_ID'),
            'keyFilePath' => getenv('GCP_SERVICE_ACCOUNT_KEY'),
        ]);

        try {
            $bucket = $storage->bucket($bucketName);
            $filename = $this->getFilename($file);
            $fullpath = $filepath . '/' . $filename;
            $object = $bucket->object($fullpath);
            if ($object->exists()) {
                // delete existing object
                $object->delete();
            }

            $bucket->upload(fopen($file->tempName, 'r'), ['name' => $fullpath]);

            $object = $bucket->object($fullpath);
            $duration = $this->signed_url_duration;
            $url = $object->signedUrl(new \DateTime('+ ' . $duration . ' seconds'));
            // create photo in db
            $photo = new Photos();
            $photo->bucket = $bucketName;
            $photo->filepath = $filepath;
            $photo->filename = $filename;
            $photo->is_trivial = Photos::NOT_TRIVIAL;
            $photo->status = ACTIVE;
            if ($data && is_array($data)){
                foreach ($data as $key=>$value){
                    $photo->$key = $value;
                }
            }
            if ($photo->save()) {
                $result = [
                    'name' => 'upload file',
                    'code' => 0,
                    'status' => 200,
                    'message' => 'success',
                    'photo' => $photo,
                    'url' => $url,
                ];
                return $result;
            } else {
                return $photo->errors;
            }
        } catch (GoogleException $e) {
            Yii::error('upload file failed with error', $e->getMessage());
            //print_r($e->getMessage());die;
            return false;
        }
    }

    /**
     * upload base64 string photo
     * @param string $bucketName
     * @param string $filepath
     * @param string $filename
     * @param string $base64
     * @param array $data supplementary data
     */
    public function uploadBase64($bucketName, $filepath, $filename, $base64, $data = null){
        $storage = new StorageClient([
            'projectId' => getenv('GCP_PROJECT_ID'),
            'keyFilePath' => getenv('GCP_SERVICE_ACCOUNT_KEY'),
        ]);

        try {
            // write base64 string to tmpfile
            $tmpfile = tmpfile();
            $path = stream_get_meta_data($tmpfile)['uri'];
            //$ifp = fopen($tmpfile, 'wb');
            $image = explode(',', $base64);
            fwrite($tmpfile, base64_decode($image[1]));
            //fclose($tmpfile); 

            Yii::info("tempfile::",$path);

            $bucket = $storage->bucket($bucketName);
            $filename = $this->getFilename($filename);
            $fullpath = $filepath . '/' . $filename;
            $object = $bucket->object($fullpath);
            if ($object->exists()) {
                // delete existing object
                $object->delete();
            }

            //$bucket->upload(fopen($path, 'r'), ['name' => $fullpath]);
            $bucket->upload($tmpfile, ['name' => $fullpath]);

            $object = $bucket->object($fullpath);
            $duration = $this->signed_url_duration;
            $url = $object->signedUrl(new \DateTime('+ ' . $duration . ' seconds'));
            //echo $url;die;
            //fclose($tmpfile); 

            // create photo in db
            $photo = new Photos();
            $photo->bucket = $bucketName;
            $photo->filepath = $filepath;
            $photo->filename = $filename;
            $photo->is_trivial = Photos::NOT_TRIVIAL;
            $photo->status = ACTIVE;
            if ($data && is_array($data)) {
                foreach ($data as $key => $value) {
                    $photo->$key = $value;
                }
            }

            if ($photo->save()) {
                $result = [
                    'name' => 'upload file',
                    'code' => 0,
                    'status' => 200,
                    'message' => 'success',
                    'photo' => $photo,
                    'url' => $url,
                ];
                return $result;
            } else {
                return $photo->errors;
            }
        } catch (GoogleException $e) {
            Yii::error('upload file failed with error', $e->getMessage());
            //print_r($e->getMessage());die;
            return false;
        }
    }

    /**
     * get signed url
     * @var Photos $photo
     */
    public function getSignedUrl($photo){
        // read url from cache, if not exist then get signed url from GCS, then save to cache
        if ($cache_url = Yii::$app->redis->get('photo_'.$photo->id)){
            Yii::info('photo url from cache', $cache_url);
            return $cache_url;
        }

        $storage = new StorageClient([
            'projectId' => getenv('GCP_PROJECT_ID'),
            'keyFilePath' => getenv('GCP_SERVICE_ACCOUNT_KEY'),
        ]);

        try {
            $bucket = $storage->bucket($photo->bucket);
            $fullpath = $photo->filepath . '/' . $photo->filename;
            $object = $bucket->object($fullpath);
            if (!$object->exists()){
                return null;
            }

            $duration = $this->signed_url_duration;
            $url = $object->signedUrl(new \DateTime('+ ' . $duration . ' seconds'));
            Yii::$app->redis->set('photo_'.$photo->id,$url,'EX', $duration - 30);
            //Yii::$app->redis->set('photo_' . $photo->id, $url);
            return $url;
        } catch (GoogleException $e) {
            Yii::error('GCS error', $e->getMessage());
            //print_r($e->getMessage());die;
            return null;
        }
    }
}