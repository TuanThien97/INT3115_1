<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackageRoleAdminSchoolModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PackageRoleAdminSchoolModules';
    public $depends = ['common\fixtures\AdminSchoolModulesFixture', 'common\fixtures\AdminSchoolModuleRoutesFixture'];
    public $dataFile = '@common/fixtures/data/package_role_admin_school_modules.php';
}
