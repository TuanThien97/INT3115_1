<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidCheckinFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidCheckins';
    public $depends = ['common\fixtures\KidFixture', 'common\fixtures\TeacherFixture'];
    public $dataFile = '@common/fixtures/data/kid_checkins.php';
}