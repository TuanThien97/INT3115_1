<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AbsentReasonFixture extends ActiveFixture
{
    public $modelClass = 'common\models\AbsentReasons';
    public $dataFile = '@common/fixtures/data/absent_reasons.php';
}
