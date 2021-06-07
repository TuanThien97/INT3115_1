<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CitiesFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Cities';
    public $dataFile = '@common/fixtures/data/cities.php';
}
