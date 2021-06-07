<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ParentsFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Parents';
    public $depends = ['common\fixtures\ProfessionalFixture', 'common\fixtures\IncomingLevelFixture'];
    public $dataFile = '@common/fixtures/data/parents.php';
}
