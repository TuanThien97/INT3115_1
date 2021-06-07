<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ParentPackageRolesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\ParentPackageRoles';
    //public $depends = ['common\fixtures\PrincipalsFixture', 'common\fixtures\PackageRolesFixture'];
    public $dataFile = '@common/fixtures/data/parent_package_roles.php';
}
