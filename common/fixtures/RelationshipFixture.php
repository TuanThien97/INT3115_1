<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class RelationshipFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Relationships';
    public $dataFile = '@common/fixtures/data/relationships.php';
}