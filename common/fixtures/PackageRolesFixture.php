<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackageRolesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\PackageRoles';
    public $depends = ['common\fixtures\PackagesFixture'];
    public $dataFile = '@common/fixtures/data/package_roles.php';
}
