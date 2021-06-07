<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class SchoolTeachersFixture extends ActiveFixture
{
    public $modelClass = 'common\models\SchoolTeachers';
    public $depends = ['common\fixtures\SchoolsFixture', 'common\fixtures\TeachersFixture'];
    public $dataFile = '@common/fixtures/data/school_teachers.php';
}
