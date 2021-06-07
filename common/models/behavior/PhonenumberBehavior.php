<?php

namespace common\models\behavior;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use common\helpers\PhoneHelper;

/**
 * PhonenumberBehavior class
 * This behavior strip leading zeros (0) from phone number field of entity
 */
class PhonenumberBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    public function beforeInsert($event)
    {
        if (isset($this->owner->phone_number) && !empty($this->owner->phone_number)){
            $this->owner->phone_number = PhoneHelper::sanitizePrefix($this->owner->phone_number);
        }
    }

    public function beforeUpdate($event)
    {
        if (isset($this->owner->phone_number) && !empty($this->owner->phone_number)) {
            $this->owner->phone_number = PhoneHelper::sanitizePrefix($this->owner->phone_number);
        }
    }
}
