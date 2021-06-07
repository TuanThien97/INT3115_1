<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackageRoleApiPrincipalModulesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PackageRoleApiPrincipalModules';
    public $depends = ['common\fixtures\ApiPrincipalModulesFixture', 'common\fixtures\ApiPrincipalModuleRoutesFixture'];
    public $dataFile = '@common/fixtures/data/package_role_api_principal_modules.php';
}
