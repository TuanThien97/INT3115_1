<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackageRoleApiParentModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PackageRoleApiParentModules';
    public $depends = ['common\fixtures\ApiParentModulesFixture', 'common\fixtures\ApiParentModuleRoutesFixture'];
    public $dataFile = '@common/fixtures/data/package_role_api_parent_modules.php';
}
