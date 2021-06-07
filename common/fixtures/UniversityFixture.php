<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class UniversityFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Universities';
    public $dataFile = '@common/fixtures/data/universities.php';
}
