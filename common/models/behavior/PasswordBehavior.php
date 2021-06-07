<?php

namespace common\models\behavior;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * PasswordBehavior class
 * this behavior generate temp password for entity
 */
class PasswordBehavior extends Behavior
{
    const PASSWORD_LENGTH = 6;
    const IS_TEMP = 1;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    public function beforeInsert($event)
    {
        if (!isset($this->owner->temp_password) || empty($this->owner->temp_password)) {
            $password = $this->generatePassword();
            if (!empty($password)) {
                $this->owner->temp_password = static::encryptPassword($password);
                $this->owner->is_temporary_password = static::IS_TEMP;
            }
        }
    }

    public function beforeUpdate($event)
    {
        if (!isset($this->owner->temp_password) || empty($this->owner->temp_password)) {
            $password = $this->generatePassword();
            if (!empty($password)) {
                $this->owner->temp_password = static::encryptPassword($password);
                $this->owner->is_temporary_password = static::IS_TEMP;
            }
        }
    }

    /**
     * generate random password
     */
    private function generatePassword(){
        if ($this->owner instanceof \common\models\Teachers) {
            return $this->owner->generateRandomPassword();
        }elseif ($this->owner instanceof \common\models\Parents) {
            return $this->owner->generateRandomPassword();
        }
        return \common\helpers\Utilities::generateRandomString(static::PASSWORD_LENGTH);
    }

    /**
     * encrypt password
     * @param string $password
     * @return string encrypted password
     */
    public static function encryptPassword($password){
        $jti = Yii::$app->params['jwt']['jti_password'];
        $expiration = Yii::$app->params['jwt']['temp_password_expiration'];
        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $jwt = Yii::$app->jwt;
        $jwtoken = $jwt->getBuilder()
            ->setIssuer(Yii::$app->params['jwt']['issuer']) // Configures the issuer (iss claim)
            ->setAudience(Yii::$app->params['jwt']['audience']) // Configures the audience (aud claim)
            ->setId($jti, true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())
            ->setExpiration(time() + $expiration)
            ->set('password', $password)
            ->sign($signer, $jwt->key) // creates a signature using [[Jwt::$key]]
            ->getToken(); // Retrieves the generated token
        return (string) $jwtoken;
    }
}