<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackageRoleApiTeacherModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PackageRoleApiTeacherModules';
    public $depends = ['common\fixtures\ApiTeacherModulesFixture', 'common\fixtures\ApiTeacherModuleRoutesFixture'];
    public $dataFile = '@common/fixtures/data/package_role_api_teacher_modules.php';
}
