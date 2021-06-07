<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ApiTeacherModuleRoutesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ApiTeacherModuleRoutes';
    public $depends = ['common\fixtures\ApiTeacherModulesFixture'];
    public $dataFile = '@common/fixtures/data/api_teacher_module_routes.php';
}
