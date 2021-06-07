<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AdminSchoolModuleRoutesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\AdminSchoolModuleRoutes';
    public $depends = ['common\fixtures\AdminSchoolModulesFixture'];
    public $dataFile = '@common/fixtures/data/admin_school_module_routes.php';
}
