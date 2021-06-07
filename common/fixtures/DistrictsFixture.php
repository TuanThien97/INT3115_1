<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class DistrictsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Districts';
    public $dataFile = '@common/fixtures/data/districts.php';
}
