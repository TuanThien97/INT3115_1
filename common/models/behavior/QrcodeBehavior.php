<?php

namespace common\models\behavior;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * QrcodeBehavior class
 * this behavior generate unique code and QRcode for entity
 */
class QrcodeBehavior extends Behavior
{
    const LIMIT_TRY = 1000;
    const CODE_LENGTH = 10;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    public function beforeInsert($event)
    {
        if (!isset($this->owner->code) || empty($this->owner->code)) {
            $code = $this->generateCode();
            if (!empty($code)) {
                $this->owner->code = $code;
                $this->owner->qrcode = \common\helpers\QrcodeHelper::base64($code);
            }
        }
    }

    public function beforeUpdate($event)
    {
        if (!isset($this->owner->code) || empty($this->owner->code)) {
            $code = $this->generateCode();
            if (!empty($code)) {
                $this->owner->code = $code;
                $this->owner->qrcode = \common\helpers\QrcodeHelper::base64($code);
            }
        }
    }

    /**
     * generate code for entity with limit try
     */
    private function generateCode()
    {
        if ($this->owner instanceof \common\models\Kids) {
            $try = 0;
            while (true) {
                $code = 'K' . \common\helpers\Utilities::generateCodeWithOnlyNumber(static::CODE_LENGTH);
                $is_exist = \common\models\Kids::find()->where(['code' => $code])->one();
                if (!$is_exist) {
                    return $code;
                } else {
                    $try++;
                    if ($try > static::LIMIT_TRY) {
                        break;
                    }
                }
            }
            return null;
        } elseif ($this->owner instanceof \common\models\Parents) {
            $try = 0;
            while (true) {
                $code = 'P' . \common\helpers\Utilities::generateCodeWithOnlyNumber(static::CODE_LENGTH);
                $is_exist = \common\models\Kids::find()->where(['code' => $code])->one();
                if (!$is_exist) {
                    return $code;
                } else {
                    $try++;
                    if ($try > static::LIMIT_TRY) {
                        break;
                    }
                }
            }
            return null;
        } elseif ($this->owner instanceof \common\models\Teachers) {
            $try = 0;
            while (true) {
                $code = 'T' . \common\helpers\Utilities::generateCodeWithOnlyNumber(static::CODE_LENGTH);
                $is_exist = \common\models\Teachers::find()->where(['code' => $code])->one();
                if (!$is_exist) {
                    return $code;
                } else {
                    $try++;
                    if ($try > static::LIMIT_TRY) {
                        break;
                    }
                }
            }
            return null;
        } elseif ($this->owner instanceof \common\models\Principals) {
            $try = 0;
            while (true) {
                $code = 'PR' . \common\helpers\Utilities::generateCodeWithOnlyNumber(static::CODE_LENGTH);
                $is_exist = \common\models\Principals::find()->where(['code' => $code])->one();
                if (!$is_exist) {
                    return $code;
                } else {
                    $try++;
                    if ($try > static::LIMIT_TRY) {
                        break;
                    }
                }
            }
            return null;
        } elseif ($this->owner instanceof \common\models\Protectors) {
            $try = 0;
            while (true) {
                $code = 'PT' . \common\helpers\Utilities::generateCodeWithOnlyNumber(static::CODE_LENGTH);
                $is_exist = \common\models\Protectors::find()->where(['code' => $code])->one();
                if (!$is_exist) {
                    return $code;
                } else {
                    $try++;
                    if ($try > static::LIMIT_TRY) {
                        break;
                    }
                }
            }
            return null;
        }
    }
}
