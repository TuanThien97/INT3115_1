<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class SchoolKidsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\SchoolKids';
    public $depends = ['common\fixtures\SchoolsFixture', 'common\fixtures\KidsFixture'];
    public $dataFile = '@common/fixtures/data/school_kids.php';
}
