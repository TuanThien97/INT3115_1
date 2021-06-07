<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidMessageFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidMessages';
    public $depends = ['common\fixtures\KidFixture', 'common\fixtures\TeacherFixture'];
    public $dataFile = '@common/fixtures/data/kid_messages.php';
}