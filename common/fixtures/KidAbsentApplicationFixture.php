<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidAbsentApplicationFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidAbsentApplication';
    public $depends = ['common\fixtures\AbsentReasonFixture', 'common\fixtures\KidFixture'];
    public $dataFile = '@common/fixtures/data/kid_absent_application.php';
}
