<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidStampFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidStamps';
    public $dataFile = '@common/fixtures/data/kid_stamps.php';
}