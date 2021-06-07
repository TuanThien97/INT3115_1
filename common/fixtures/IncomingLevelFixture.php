<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class IncomingLevelFixture extends ActiveFixture
{
    public $modelClass = 'common\models\IncomingLevels';
    public $dataFile = '@common/fixtures/data/incoming_levels.php';
}
