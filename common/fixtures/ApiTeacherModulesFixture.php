<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiTeacherModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiTeacherModules';
    public $dataFile = '@common/fixtures/data/api_teacher_modules.php';
}
