<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class PackageRoleAdminSchoolModules extends \common\models\db\PackageRoleAdminSchoolModulesDB {
    // add timestamp behavior
	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
	        ],
	    ];
	}
}