<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TeachersFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Teachers';
    public $depends = ['common\fixtures\UniversityFixture'];
    public $dataFile = '@common/fixtures/data/teachers.php';
}
