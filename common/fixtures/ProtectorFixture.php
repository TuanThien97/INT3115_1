<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ProtectorFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Protectors';
    public $depends = ['common\fixtures\RelationshipFixture', 'common\fixtures\KidFixture'];
    public $dataFile = '@common/fixtures/data/protectors.php';
}