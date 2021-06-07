<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class SchoolsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Schools';
    public $depends = ['common\fixtures\DistrictsFixture'];
    public $dataFile = '@common/fixtures/data/schools.php';
}
