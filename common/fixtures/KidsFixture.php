<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Kids';
    public $depends = ['common\fixtures\ParentsFixture'];
    public $dataFile = '@common/fixtures/data/kids.php';
}