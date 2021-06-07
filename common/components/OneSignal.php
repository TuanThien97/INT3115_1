<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

class OneSignal extends Component {
    // API key
    public $app_id_teacher = null;
    public $api_key_teacher = null;
    public $app_id_parent = null;
    public $api_key_parent = null;
    public $app_id_principal = null;
    public $api_key_principal = null;

    // restapi endpoint
    public $endpoint = 'https://onesignal.com/api/v1/notifications';

    // app name
    public $app_parent = 'buffalo';
    public $app_teacher = 'mouse';
    public $app_principal = 'tiger';

    const PUSH_OPTION = 'push';
    const PUSH_ENABLE = 'enable';
    const PUSH_DISABLE = 'disable'; 

    /**
     * send notification to user
     * @param int $user_id
     * @param array $data
     * @param string $target_app optional
     */
    public function sendNotification($user_id,$data,$target_app=null){
        // use sample code from Onesignal
        $content = array(
            "en" => $data['content']
        );
        $push_enable = true;

        if (!$target_app){
            $target_app = Yii::$app->id;
        }
        if ($target_app == $this->app_parent){
            $app_id = $this->app_id_parent;
            $api_key = $this->api_key_parent;
            $user_setting = \common\models\ParentSettings::findOne(['parent_id'=>$user_id,'option'=>static::PUSH_OPTION,'status'=>ACTIVE]);
            if ($user_setting && $user_setting->value == static::PUSH_DISABLE){
                $push_enable = false;
            }
        }else if($target_app == $this->app_teacher){
            $app_id = $this->app_id_teacher;
            $api_key = $this->api_key_teacher;
            $user_setting = \common\models\TeacherSettings::findOne(['teacher_id' => $user_id, 'option' => static::PUSH_OPTION, 'status' => ACTIVE]);
            if ($user_setting && $user_setting->value == static::PUSH_DISABLE) {
                $push_enable = false;
            }
        } else if ($target_app == $this->app_principal) {
            $app_id = $this->app_id_principal;
            $api_key = $this->api_key_principal;
            $user_setting = \common\models\PrincipalSettings::findOne(['principal_id' => $user_id, 'option' => static::PUSH_OPTION, 'status' => ACTIVE]);
            if ($user_setting && $user_setting->value == static::PUSH_DISABLE) {
                $push_enable = false;
            }
        } else{
            return null;
        }

        // check push enable
        if (!$push_enable){
            return null;
        }

        $fields = array(
            'app_id' => $app_id,
            'filters' => array(array("field" => "tag", "key" => "uid", "relation" => "=", "value" => $user_id)),
            'data' => $data['data'],
            'contents' => $content
        );

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $api_key,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $return["allresponses"] = $response;
        $return = json_encode($return);

        //print("\n\nJSON received:\n");
        //print($return);
        //print("\n");

        return $return;
    }
}