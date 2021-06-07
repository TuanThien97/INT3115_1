<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AdminSchoolModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\AdminSchoolModules';
    public $dataFile = '@common/fixtures/data/admin_school_modules.php';
}
