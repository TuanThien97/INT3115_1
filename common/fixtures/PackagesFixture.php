<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PackagesFixture extends ActiveFixture
{
    public $db = 'db_auth';
    public $modelClass = 'common\models\Packages';
    //public $depends = ['common\fixtures\ProfessionalFixture', 'common\fixtures\IncomingLevelFixture'];
    public $dataFile = '@common/fixtures/data/packages.php';
}
