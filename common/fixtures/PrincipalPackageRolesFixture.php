<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PrincipalPackageRolesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PrincipalPackageRoles';
    //public $depends = ['common\fixtures\PrincipalsFixture', 'common\fixtures\PackageRolesFixture'];
    public $dataFile = '@common/fixtures/data/principal_package_roles.php';
}
