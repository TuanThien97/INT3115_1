<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class SchoolPackagesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\SchoolPackages';
    //public $depends = ['common\fixtures\DistrictsFixture'];
    public $dataFile = '@common/fixtures/data/school_packages.php';
}
