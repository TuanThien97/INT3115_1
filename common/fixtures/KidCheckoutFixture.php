<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class KidCheckoutFixture extends ActiveFixture
{
    public $modelClass = 'common\models\KidCheckouts';
    public $depends = ['common\fixtures\KidFixture', 'common\fixtures\ProtectorFixture'];
    public $dataFile = '@common/fixtures/data/kid_checkouts.php';
}