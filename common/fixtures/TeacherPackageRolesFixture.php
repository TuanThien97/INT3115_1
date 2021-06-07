<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TeacherPackageRolesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\TeacherPackageRoles';
    //public $depends = ['common\fixtures\PrincipalsFixture', 'common\fixtures\PackageRolesFixture'];
    public $dataFile = '@common/fixtures/data/teacher_package_roles.php';
}
